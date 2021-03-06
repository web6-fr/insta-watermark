<?php
/**
 * Upload
 *
 * PHP version 7
 *
 * @category  Class
 * @package   W6\InstaWatermark
 * @author    WEB6 <contact@web6.fr>
 * @copyright 2018 WEB6
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt  GNU GPLv3
 * @link      https://github.com/web6-fr/insta-watermark
 * @since     1.0.0
 */

namespace W6\InstaWatermark;

/**
 * This class handles a single file upload
 *
 * @category  Class
 * @package   W6\InstaWatermark\App
 * @author    WEB6 <contact@web6.fr>
 * @copyright 2018 WEB6
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt  GNU GPLv3
 * @link      https://github.com/web6-fr/insta-watermark
 */
class Upload
{
    /**
     * $_FILES key
     *
     * @var string
     */
    public $key;

    /**
     * Convert to JPG
     *
     * @var bool
     */
    public $convert;

    /**
     * New file name
     *
     * @var string
     */
    public $filename;

    /**
     * Final file path
     *
     * @var string
     */
    public $path;

    /**
     * Constructor
     *
     * @param $key string $_FILES key.
     * @param $convert bool Convert to JPG
     *
     * @return void
     */
    public function __construct($key, $convert = true)
    {
        $this->key = $key;
        $this->convert = $convert;
    }

    /**
     * Handle file upload
     *
     * @return void
     */
    public function handle()
    {
        if (!isset($_FILES[$this->key])) {
            throw new \Exception('No files were uploaded');
        }
        $saveFolderPath = Config::get('folderPath');
        $this->filename = $this->key.'_'.uniqid();
        $upload = new \upload($_FILES[$this->key]);
        $upload->file_max_size = Config::get('maxUploadSize');
        $upload->file_new_name_body = $this->filename;
        $upload->allowed = ['image/*'];
        $upload->image_resize = false;
        if ($this->convert) {
            $upload->image_convert = 'jpg';
        }
        $upload->process($saveFolderPath);
        if ($upload->processed) {
            $upload->clean();
            $this->path = $saveFolderPath.'/'.$upload->file_dst_name;
            return true;
        }
        throw new \Exception('An error has occured : '.$upload->error);
    }
}
