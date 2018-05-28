<?php
namespace Tizer;

class Logger {

    protected $options = array (
        'extension'         => 'txt',
        'dateFormat'        => 'Y-m-d H:i:s.u',
        'filename'          => false,
        'prefix'            => 'log_',
        'logFormat'         => false,
        'messageDelimiter'  => "\t",
        'fileLock'          => true,
        'fileLockTry'       => 100,
    );

    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';

    protected $logLevelThreshold = self::DEBUG;

    protected $logLevels = [
        self::EMERGENCY     => 0,
        self::ALERT         => 1,
        self::CRITICAL      => 2,
        self::ERROR         => 3,
        self::WARNING       => 4,
        self::NOTICE        => 5,
        self::INFO          => 6,
        self::DEBUG         => 7
    ];

    private $_filePath      = null;
    private $_fileHandle    = null;
    private $_defaultPermission = 0777;

    public function __construct($logDirectory, $logLevelThreshold = self::DEBUG, array $options = []) {
        $this->logLevelThreshold = $logLevelThreshold;
        $this->options = array_merge($this->options, $options);

        $logDirectory = rtrim($logDirectory, DIRECTORY_SEPARATOR);
        if (!is_dir($logDirectory)) {
            mkdir($logDirectory, $this->_defaultPermission, true);
        }

        $this->_setLogFilePath($logDirectory);

        return $this;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * Open log file
     *
     * @return $this
     */
    protected function open() {
        if (!is_null($this->_fileHandle)) {
            return $this;
        }

        $this->_fileHandle = fopen($this->_filePath, 'a+');
        if ($this->options['fileLock']) {
            $n = 0;
            while (!flock($this->_fileHandle, LOCK_EX | LOCK_NB)) {
                if ($n >= $this->options['fileLockTry']) {
                    throw new \Exception("Can't lock LOG file: {$this->_filePath}");
                    break;
                }

                usleep(round(rand(0, 100) * 1000));
                $n++;
            }
        }

        return $this;
    }

    /**
     * Write message to log file
     *
     * @param $message
     * @return $this
     */
    protected function write($message) {
        $this->open();

        fwrite($this->_fileHandle, $message);

        return $this;
    }

    /**
     * Truncate log file
     *
     * @return $this
     */
    protected function truncate() {
        $this->open();

        ftruncate($this->_fileHandle, 0);
        rewind($this->_fileHandle);

        return $this;
    }

    /**
     * Close log file
     *
     * @return $this
     */
    protected function close() {
        if (is_null($this->_fileHandle)) {
            return $this;
        }

        if ($this->options['fileLock']) {
            fflush($this->_fileHandle);
            flock($this->_fileHandle, LOCK_UN);
        }

        fclose($this->_fileHandle);
        $this->_fileHandle = null;

        return $this;
    }

    /**
     * Complete log
     *
     * @return $this
     */
    public function complete() {
        $this->close();

        return $this;
    }

    public function log($message, $level = null) {
        if (is_null($level)) {
            $level = $this->logLevelThreshold;
        }

        $message = $this->formatMessage($level, $message);
        $this->write($message);

        return $this;
    }

    /**
     * Generation message line
     *
     * @param $level
     * @param $message
     * @return string
     */
    protected function formatMessage($level, $message) {
        if ($this->options['logFormat']) {
            $parts = array(
                'date'          => $this->getTimestamp(),
                'level'         => strtoupper($level),
                'level-padding' => str_repeat(' ', 9 - strlen($level)),
                'priority'      => $this->logLevels[$level],
                'message'       => $this->prepareMessage($message),
            );

            $message = $this->options['logFormat'];
            foreach ($parts as $part => $value) {
                $message = str_replace('{' . $part . '}', $value, $message);
            }
        }
        else {
            $message = "[{$this->getTimestamp()}]{$this->options['messageDelimiter']}[{$level}]{$this->options['messageDelimiter']}" . $this->prepareMessage($message);
        }

        return $message . PHP_EOL;
    }

    /**
     * Array message to string
     *
     * @param $message
     * @return string
     */
    protected function prepareMessage($message) {
        if (is_array($message)) {
            return implode($this->options['messageDelimiter'], $message);
        }

        return $message;
    }

    /**
     * Return datetime
     *
     * @return string
     */
    protected function getTimestamp() {
        $originalTime = microtime(true);

        $micro = sprintf("%06d", ($originalTime - floor($originalTime)) * 1000000);

        $date = new \DateTime(date('Y-m-d H:i:s.' . $micro, $originalTime));

        return $date->format($this->options['dateFormat']);
    }

    /**
     * Set datetime format
     *
     * @param $dateFormat
     * @return $this
     */
    public function setDateFormat($dateFormat) {
        $this->options['dateFormat'] = $dateFormat;

        return $this;
    }

    /**
     * Generation log filename
     *
     * @param $logDirectory
     * @return $this
     */
    private function _setLogFilePath($logDirectory) {
        if ($this->options['filename']) {
            if (preg_match('@\.(txt|log)$@', $this->options['filename'])) {
                $this->_filePath = $logDirectory . DIRECTORY_SEPARATOR . $this->options['filename'];
            }
            else {
                $this->_filePath = $logDirectory . DIRECTORY_SEPARATOR . $this->options['filename'] . '.' . $this->options['extension'];
            }
        }
        else {
            $this->_filePath = $logDirectory . DIRECTORY_SEPARATOR . $this->options['prefix'] . date('Y-m-d') . '.' . $this->options['extension'];
        }

        return $this;
    }

}