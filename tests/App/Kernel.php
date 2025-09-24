<?php

namespace Tito10047\AltchaBundle\Tests\App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel {
	use MicroKernelTrait;


    public function bootProject(): void
    {
        if (!$this->booted && $this->preBootHandler) {
            call_user_func_array($this->preBootHandler, [$this]);
        }
        parent::boot();
    }

	/**
	 * Gets the path to the bundles configuration file.
	 */
	private function getBundlesPath(): string {
		return  __DIR__ . '/' . $this->configDir .'/bundles.php';
	}

	public function getConfigDir() {
        if (!$this->configDir) {
            return __DIR__ . '/config';
        }
        return __DIR__ . '/' . $this->configDir;
    }


    public function __construct(string $env, private readonly ?string $configDir, private mixed $preBootHandler = null)
    {
        parent::__construct($env, true);
	}


	public function getCacheDir(): string {
        return $this->getProjectDir() . '/var/cache/'.$this->configDir.'/' . $this->environment;
	}



	public function __destruct() {
		//remove entire cache dir recursively
//		$this->removeDir($this->getCacheDir());
	}

	private function removeDir(string $dir): void {
		$files = scandir($dir);
		foreach ($files as $file) {
			if ($file === '.' || $file === '..') {
				continue;
			}
			if (is_dir($dir . '/' . $file)) {
				$this->removeDir($dir . '/' . $file);
			} else {
				unlink($dir . '/' . $file);
			}
		}
		rmdir($dir);
	}


}