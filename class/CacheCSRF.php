<?php
/**
 * CSRF form protection using cache
 *
 * @see Zend_Form_Element_Hash
 */

class CacheCSRF
{
    /**
     * Actual hash used.
     * @var mixed
     */
    protected $_hash = null;
    
    /**
     * @var string
     */
    protected $_name = 'csrf';

    /**
     * Salt for CSRF token
     * @var string
     */
    protected $_salt = 'salt';

    /**
     * @var Zend_Cache_Backend_Interface
     */
    protected $_cache = null;

    /**
     * TTL for CSRF token
     * @var int
     */
    protected $_timeout = 300;
    
    /**
     * Initialize CSRF validator
     *
     * @return App_Zend_CacheCSRF
     */
    public function generateHash()
    {
        // Generat new one
        $this->_hash = md5(
            mt_rand(1,1000000)
            .  $this->getSalt()
            .  $this->getName()
            .  mt_rand(1,1000000)
        );
        
        // Add it to cache
        cache_set(
            $this->_hash . '-csrf', 
            true, 
            'cache', 
            time() + $this->_timeout
        );
        
        return $this;
    }
    
    /**
     * Validate CSRF token and optionally invalidate it after check
     * 
     * @return boolean
     */
    public function validateHash($token, $invalidate = true)
    {
        $cachedData = cache_get($token . '-csrf');
        if (!empty($cachedData->data)) {
            if ($invalidate) {
                $this->invalidateHash($token);
            }
            return true;
        }
        
        return false;
    }
    
    /**
     * Delete CSRF token
     * 
     * @return boolean
     */
    public function invalidateHash($token)
    {
        cache_set($token, null, 'cache', time() - 1000);        
        return true;
    }
    
    /**
     * Retrieve CSRF token
     *
     * If no CSRF token currently exists, generates one.
     *
     * @return string
     */
    public function getHash()
    {
        if (empty($this->_hash)) {
            $this->generateHash();
        }
        
        return $this->_hash;
    }
    
    /**
     * Name for CSRF token
     *
     * @param  string $name
     * @return App_Zend_CacheCSRF
     */
    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

    /**
     * Retrieve name for CSRF token
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Salt for CSRF token
     *
     * @param  string $salt
     * @return App_Zend_CacheCSRF
     */
    public function setSalt($salt)
    {
        $this->_salt = (string) $salt;
        return $this;
    }

    /**
     * Retrieve salt for CSRF token
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->_salt;
    }
    
    /**
     * Set cache object
     *
     * @param  Zend_Cache_Backend_Interface $cache
     * @return App_Zend_CacheCSRF
     */
    public function setCache($cache)
    {
        $this->_cache = $cache;
        return $this;
    }

    /**
     * Get cache object
     *
     * @return Zend_Cache_Backend_Interface
     */
    public function getCache()
    {
        return $this->_cache;
    }
    
    /**
     * Set timeout for CSRF cache token
     *
     * @param  int $ttl
     * @return App_Zend_CacheCSRF
     */
    public function setTimeout($ttl)
    {
        $this->_timeout = (int) $ttl;
        return $this;
    }

    /**
     * Get CSRF cache timeout
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->_timeout;
    }
    
}
