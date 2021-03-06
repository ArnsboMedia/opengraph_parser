<?php
namespace OpenGraphParser;
class OpenGraphParser {
    protected $fetchStrategy;
    protected $cacheAdapter;

    public static function Http($options=array()) {
        $options = array_merge($options, array('fetchStrategy' => new HttpFetchStrategy()));
        $obj = new OpenGraphParser($options);
        return $obj;
    }

    public static function File($options=array()) {
        $options = array_merge($options, array('fetchStrategy' => new FileFetchStrategy()));
        $obj = new OpenGraphParser($options);
        return $obj;
    }

    /**
     * Constructs a new parser with the given options
     *
     * Currently only fetchStrategy is used
     */
    public function __construct($options=array()) {
        $fetchStrategy = null;
        $cacheAdapter = null;

        extract($options);

        if(is_null($fetchStrategy)) {
            $fetchStrategy = new FetchStrategy();
        }

        $this->fetchStrategy = $fetchStrategy;
        if(!is_null($cacheAdapter)) {
            $this->fetchStrategy->setCacheAdapter($cacheAdapter);
        }
        $this->cacheAdapter = $cacheAdapter;
    }

    public function parse($uri) {
        return $this->fetchStrategy->get($uri);
    }

    public function parseList($urls) {
        $out = new ResultCollection;
        foreach($urls as $url) {
            try {
            $out->add($this->parse($url));
            } catch(\Exception $ex) {
                // skip exceptions
            }
        }
        return $out;
    }

    public function setFetchStrategy(AbstractFetchStrategy $strategy) {
        if(!is_null($this->cacheAdapter)) {
            $strategy->setCacheAdapter($this->cacheAdapter);
        }

        $this->fetchStrategy = $strategy;
    }

    public function getFetchStrategy() {
        return $this->fetchStrategy;
    }
    
    public function setCacheAdapter($cacheAdapter) {
        $this->fetchStrategy->setCacheAdapter($cacheAdapter);

        $this->cacheAdapter = $cacheAdapter;
    }

    public function getCacheAdapter() {
        return $this->cacheAdapter;
    }
}
