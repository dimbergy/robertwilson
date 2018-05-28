<?php
/**
 * @version		$Id$
 * @package		Koowa_Template
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

 /**
  * Abstract Template class
  *
  * @author		Johan Janssens <johan@nooku.org>
  * @package	Koowa_Template
  */
abstract class KTemplateAbstract extends KObject implements KTemplateInterface
{
    /**
     * The template path
     *
     * @var string
     */
    protected $_path;

    /**
     * The template data
     *
     * @var array
     */
    protected $_data;

    /**
     * The template contents
     *
     * @var string
     */
    protected $_content;

    /**
     * The set of template filters for templates
     *
     * @var array
     */
    protected $_filters;

    /**
     * View object or identifier
     *
     * @var    string|object
     */
    protected $_view;

    /**
     * Counter
     *
     * Used to track recursive calls during template evaluation
     *
     * @var int
     * @see _evaluate()
     */
    private $__counter;

    /**
     * Constructor
     *
     * Prevent creating instances of this class by making the constructor private
     *
     * @param KConfig $config   An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

		// Set the view identifier
    	$this->_view = $config->view;

        // Set the template data
        $this->_data = $config->data;

		 // Mixin a command chain
        $this->mixin(new KMixinCommandchain($config->append(array('mixer' => $this))));

        //Attach the filters
        $this->addFilter($config->filters);

        //Reset the counter
        $this->__counter = 0;
	}

 	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  KConfig $config  An optional KConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
            'data'             => array(),
            'filters'          => array(),
            'view'             => null,
            'command_chain' 	=> new KCommandChain(),
    		'dispatch_events'   => false,
    		'enable_callbacks' 	=> false,
        ));

        parent::_initialize($config);
    }

	/**
	 * Get the template path
	 *
	 * @return	string
	 */
	public function getPath()
	{
		return $this->_path;
	}

	/**
	 * Get the template data
	 *
	 * @return	mixed
	 */
	public function getData()
	{
		return $this->_data;
	}

    /**
     * Get the template content
     *
     * @return  string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Get the view object attached to the template
     *
     * @throws	\UnexpectedValueException	If the views doesn't implement the KViewInterface
     * @return  KViewInterface
     */
    public function getView()
    {
        if(!$this->_view instanceof KViewAbstract)
        {
            //Make sure we have a view identifier
            if(!($this->_view instanceof KServiceIdentifier)) {
                $this->setView($this->_view);
            }

            $this->_view = $this->getService($this->_view);

            //Make sure the view implements KViewAbstract
            if(!$this->_view instanceof KViewAbstract)
            {
                throw new UnexpectedValueException(
                    'View: '.get_class($this->_view).' does not implement KViewInterface'
                );
            }
        }

        return $this->_view;
    }

	/**
	 * Method to set a view object attached to the controller
	 *
	 * @param	mixed	$view An object that implements KObjectServiceable, KServiceIdentifier object
	 * 					or valid identifier string
	 * @throws	KTemplateException	If the identifier is not a view identifier
	 * @return	KTemplateAbstract
	 */
	public function setView($view)
	{
		if(!($view instanceof KViewAbstract))
		{
			if(empty($view) || (is_string($view) && strpos($view, '.') === false))
		    {
			    $identifier			= clone $this->getIdentifier();
			    $identifier->path	= array('view');
			    if ($view) {
			        $identifier->path[] = $view;
			    }
			    $identifier->name	= KRequest::format() ? KRequest::format() : 'html';
			}
			else $identifier = $this->getIdentifier($view);

			if($identifier->path[0] != 'view') {
				throw new KTemplateException('Identifier: '.$identifier.' is not a view identifier');
			}

			$view = $identifier;
		}

		$this->_view = $view;

		return $this;
	}

	/**
	 * Load a template by identifier
	 *
	 * This functions only accepts full identifiers of the format
	 * -  com:[//application/]component.view.[.path].name
	 *
	 * @param   string 	The template identifier
	 * @param	array	An associative array of data to be extracted in local template scope
     * @throws \InvalidArgumentException If the template could not be found
	 * @return KTemplateAbstract
	 */
	public function loadIdentifier($template, $data = array())
	{
	    //Identify the template
	    $identifier = $this->getIdentifier($template);

	    // Find the template
		$file = $this->findFile(dirname($identifier->filepath).'/'.$identifier->name.'.php');

		if ($file === false) {
			throw new InvalidArgumentException('Template "' . $identifier->name . '" not found');
		}

		// Load the file
		$this->loadFile($file, $data);

		return $this;
	}

	/**
     * Load a template by path
     *
     * @param   string  $file     The template path
     * @param   array   $data     An associative array of data to be extracted in local template scope
     * @return KTemplateAbstract
     */
    public function loadFile($path, $data = array())
    {
        //Store the original path
        $this->_path = $path;

        //Get the file contents
        $contents = file_get_contents($path);

        //Load the contents
        $this->loadString($contents, $data);

		return $this;
	}

    /**
     * Load a template from a string
     *
     * @param  string   $string     The template contents
     * @param  array    $data       An associative array of data to be extracted in local template scope
     * @return KTemplateAbstract
     */
	public function loadString($string, $data = array())
	{
		$this->_content = $string;

		// Merge the data
	    $this->_data = array_merge((array) $this->_data, $data);

        // Process inline templates
        if($this->__counter > 0) {
            $this->render();
        }

		return $this;
	}

    /**
     * Render the template
     *
     * @return string  The rendered data
     */
    public function render()
    {
        //Parse the template
        $this->_parse($this->_content);

        //Evaluate the template
        $this->_evaluate($this->_content);

        //Process the template only at the end of the render cycle.
        if($this->__counter == 0) {
            $this->_process($this->_content);
        }

        return $this->_content;
    }

    /**
     * Check if the template is in a render cycle
     *
     * @return boolean Return TRUE if the template is being rendered
     */
    public function isRendering()
    {
        return (bool) $this->_counter;
    }

	/**
     * Check if a filter exists
     *
     * @param 	string	The name of the filter
     * @return  boolean	TRUE if the filter exists, FALSE otherwise
     */
	public function hasFilter($filter)
	{
	    return isset($this->_filters[$filter]);
	}

	/**
	 * Adds one or more filters for template transformation
	 *
	 * @param array 	Array of one or more behaviors to add.
	 * @return KTemplate
	 */
	public function addFilter($filters)
 	{
 		$filters =  (array) KConfig::unbox($filters);

 	    foreach($filters as $filter)
		{
			if(!($filter instanceof KTemplateFilterInterface)) {
				$filter = $this->getFilter($filter);
			}

			//Enqueue the filter in the command chain
			$this->getCommandChain()->enqueue($filter);

			//Store the filter
			$this->_filters[$filter->getIdentifier()->name] = $filter;
		}

		return $this;
 	}

 	/**
	 * Get a filter by identifier
	 *
	 * @return KTemplateFilterInterface
	 */
 	 public function getFilter($filter)
 	 {
         //Create the complete identifier if a partial identifier was passed
        if(is_string($filter) && strpos($filter, '.') === false )
        {
            $identifier = clone $this->getIdentifier();
            $identifier->path = array('template', 'filter');
            $identifier->name = $filter;
        }
        else $identifier = KService::getIdentifier($filter);

        if (!isset($this->_filters[$identifier->name]))
        {
            $filter = KService::get($identifier);

            if(!($filter instanceof KTemplateFilterInterface)) {
			    throw new KTemplateException("Template filter $identifier does not implement KTemplateFilterInterface");
		    }
        }
        else $filter = $this->_filters[$identifier->name];

        return $filter;
 	 }

	/**
	 * Get a template helper
	 *
	 * @param	mixed	KServiceIdentifierInterface
	 * @return 	KTemplateHelperInterface
	 */
	public function getHelper($helper)
	{
		//Create the complete identifier if a partial identifier was passed
		if(is_string($helper) && strpos($helper, '.') === false )
		{
            $identifier = clone $this->getIdentifier();
            $identifier->path = array('template','helper');
            $identifier->name = $helper;
		}
		else $identifier = $this->getIdentifier($helper);

		//Create the template helper
		$helper = $this->getService($identifier, array('template' => $this));

	    //Check the helper interface
        if(!($helper instanceof KTemplateHelperInterface)) {
            throw new KTemplateHelperException("Template helper $identifier does not implement KTemplateHelperInterface");
        }

		return $helper;
	}

	/**
	 * Load a template helper
	 *
	 * This functions accepts a partial identifier, in the form of helper.function. If a partial
	 * identifier is passed a full identifier will be created using the template identifier.
	 *
	 * @param	string	Name of the helper, dot separated including the helper function to call
	 * @param	mixed	Parameters to be passed to the helper
	 * @return 	string	Helper output
	 */
	public function renderHelper($identifier, $params = array())
	{
		//Get the function to call based on the $identifier
		$parts    = explode('.', $identifier);
		$function = array_pop($parts);

		$helper = $this->getHelper(implode('.', $parts));

		//Call the helper function
		if (!is_callable( array( $helper, $function ) )) {
			throw new KTemplateHelperException( get_class($helper).'::'.$function.' not supported.' );
		}

		return $helper->$function($params);
	}

	/**
	 * Searches for the file
	 *
	 * @param	string	The file path to look for.
	 * @return	mixed	The full path and file name for the target file, or FALSE
	 * 					if the file is not found
	 */
	public function findFile($file)
	{
        $result = false;
        $path = dirname($file);

        // is the path based on a stream?
        if (strpos($path, '://') === false)
        {
            // not a stream, so do a realpath() to avoid directory
            // traversal attempts on the local file system.
            $path = realpath($path); // needed for substr() later
            $file = realpath($file);
        }

        // The substr() check added to make sure that the realpath()
        // results in a directory registered so that non-registered directores
        // are not accessible via directory traversal attempts.
        if (file_exists($file) && substr($file, 0, strlen($path)) == $path) {
            $result = $file;
        }

        // could not find the file in the set of paths
        return $result;
	}

	/**
	 * Returns a directory path for temporary files
	 *
	 * @return string Folder path
	 */
	protected function _getTemporaryDirectory()
	{
		return JPATH_ROOT.'/tmp';
	}

	/**
	 * Creates a file with a unique file name
	 *
	 * @param string|null $directory Uses the result of _getTemporaryDirectory() by default
	 * @return string File path
	 */
	protected function _getTemporaryFile($directory = null)
	{
		if ($directory === null) {
			$directory = $this->_getTemporaryDirectory();
		}

		$name = str_replace('.', '', uniqid('tmpl', true));
		$path = $directory.'/'.$name;

		touch($path);

		return $path;
	}

    /**
     * Parse and compile the template to PHP code
     *
     * This function passes the template through read filter chain and returns the result.
     *
     * @return string The parsed data
     */
    protected function _parse(&$content)
    {
        $context = $this->getCommandContext();

        $context->data = $content;
        $this->getCommandChain()->run(KTemplateFilter::MODE_READ, $context);
        $content = $context->data;
    }

    /**
     * Evaluate the template using a simple sandbox
     *
     * This function writes the template to a temporary file and then includes it.
     *
     * @return string The evaluated data
     * @see tempnam()
     */
    protected function _evaluate(&$content)
    {
        //Increase counter
        $this->__counter++;

        //Create temporary file
        $tempfile = $this->_getTemporaryFile();

        //Write the template to the file
        $handle = fopen($tempfile, "w+");
        fwrite($handle, $content);
        fclose($handle);

        //Include the file
        extract($this->_data, EXTR_SKIP);

        ob_start();
        include $tempfile;
        $content = ob_get_clean();

        unlink($tempfile);

        //Reduce counter
        $this->__counter--;;
    }

    /**
     * Process the template
     *
     * This function passes the template through write filter chain and returns the result.
     *
     * @return string  The rendered data
     */
    protected function _process(&$content)
    {
        $context = $this->getCommandContext();

        $context->data = $content;
        $this->getCommandChain()->run(KTemplateFilter::MODE_WRITE, $context);
        $content = $context->data;
    }

    /**
     * Returns the template contents
     *
     * @return  string
     * @see getContents()
     */
    public function __toString()
    {
        return $this->getContent();
    }
}
