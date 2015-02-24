<?php

namespace Fotkomotko\misc;
use Composer\Script\Event;

class Installer
{
    public static function postInstall(Event $event)
    {
        $composer = $event->getComposer();
        
		// do stuff
		self::warmCache();
    }
	
    public static function postUpdate(Event $event)
    {
        $composer = $event->getComposer();
		
        // do stuff
		self::warmCache();
		self::deleteCache();
    }

    private static function warmCache()
    {
        // make cache toasty
		$cacheDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'cache';
		if (!file_exists($cacheDir)) { 
			if (@mkdir($cacheDir)) {
				@chmod($cacheDir, 0777);
			}
		}
    }
	
    private static function deleteCache()
    {
        // make cache toasty
		$cacheDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'cache';
		$files = glob($cacheDir . DIRECTORY_SEPARATOR . '*', GLOB_NOSORT);
		if (!empty($files)) {
			foreach ($files as $file) {
				@unlink($file);
			}
		}
    }
	
}