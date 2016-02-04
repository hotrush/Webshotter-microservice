<?php

namespace App\Filesystem;

use Illuminate\Filesystem\FilesystemManager;
use Aws\S3\S3Client;

class PublicUrlManager extends FilesystemManager
{
    /**
     * Return a public url for file in storage
     *
     * @param null $name
     * @param string $object_path
     * @return mixed
     */
    public function publicUrl($name = null, $object_path = '')
    {
        $name = $name ?: $this->getDefaultDriver();
        $config = $this->getConfig($name);

        return $this->{'get' . ucfirst($config['driver']) . 'PublicUrl'}($config, $object_path);
    }

    /**
     * Get public url for file in local disk
     *
     * @param $config
     * @param string $object_path
     * @return string
     */
    public function getLocalPublicUrl($config, $object_path = '')
    {
        return app('url')->to('/webshots') . '/' . ltrim($object_path, '/');
    }

    /**
     * Get public url for file in S3
     *
     * @param $config
     * @param string $object_path
     * @return string
     */
    public function getS3PublicUrl($config, $object_path = '')
    {
        $config += ['version' => 'latest'];

        if ($config['key'] && $config['secret']) {
            $config['credentials'] = array_intersect_key($config, array_flip(array('key', 'secret')));
        }

        return (new S3Client($config))->getObjectUrl($config['bucket'], $object_path);
    }

    // @todo implement for rackspace
}
