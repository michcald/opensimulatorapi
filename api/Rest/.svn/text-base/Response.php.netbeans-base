<?php

abstract class Rest_Response
{
    protected $status = null; // code
    
    protected $body = null;
    
    protected $contentType = null; // json|xml
    
    const STATUS_CONTINUE = 100;
    const STATUS_SWITCHING_PROTOCOL = 101;
    const STATUS_OK = 200;
    const STATUS_CREATED = 201;
    const STATUS_ACCEPTED = 202;
    const STATUS_NONAUTHORITATIVE_INFO = 203;
    const STATUS_NO_CONTENT = 204;
    const STATUS_RESET_CONTENT = 205;
    const STATUS_PARTIAL_CONTENT = 206;
    const STATUS_MULTIPLE_CHOISES = 300;
    const STATUS_MOVED_PERMANENTLY = 301;
    const STATUS_FOUND = 302;
    const STATUS_SEE_OTHER = 303;
    const STATUS_NOT_MODIFIED = 304;
    const STATUS_USE_PROXY = 305;
    const STATUS_UNUSED = 306;
    const STATUS_TEMPORARY_REDIRECT = 307;
    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_PAYMENT_REQUIRED = 402;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_METHOD_NOT_ALLOWED = 405;
    const STATUS_NOT_ACCEPTABLE = 406;
    const STATUS_PROXY_AUTHENTICATION_REQUIRED = 407;
    const STATUS_REQUEST_TIMEOUT = 408;
    const STATUS_CONFLICT = 409;
    const STATUS_GONE = 410;
    const STATUS_LENGTH_REQUIRED = 411;
    const STATUS_PRECONDITION_FAILED = 412;
    const STATUS_REQUEST_ENTITY_TOO_LARGE = 413;
    const STATUS_REQUEST_URI_TOO_LONG = 414;
    const STATUS_UNSUPPORTED_MEDIA_TYPE = 415;
    const STATUS_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const STATUS_EXPECTATION_FAILED = 417;
    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const STATUS_NOT_IMPLEMENTED = 501;
    const STATUS_BAD_GATEWAY = 502;
    const STATUS_SERVICE_UNAVAILABLE = 503;
    const STATUS_GATEWAY_TIMEOUT = 504;
    const STATUS_HTTP_VERSION_NOT_SUPPORTED = 505;
    
    private $statusMessages = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported');
    
    public final function __construct()
    {
        $this->setStatus(self::STATUS_OK);
    }
    
    public function getContentType()
    {
        return $this->contentType;
    }
    
    public final function setStatus($status)
    {
        $this->status = $status;
    }
    
    public final function setBody(array $data)
    {
        $this->body = $this->formatData($data);
    }
    
    abstract protected function formatData($data);
    
    public final function __toString()
    {
        header("HTTP/1.1 {$this->status} {$this->statusMessages[$this->status]}");
        header("Content-Type: $this->contentType");
        
        if($this->body) {
            return $this->body;
        }
        
        // if all is ok, but without the body
        if($this->status == self::STATUS_OK)
        {
            return $this->formatData(array(
                'status' => array(
                    'message' => $this->statusMessages[self::STATUS_NO_CONTENT],
                    'code' => self::STATUS_NO_CONTENT
                )
            ));
        }
        
        // if something goes wrong
	return $this->formatData(array(
            'error' => array(
                'message' => $this->statusMessages[$this->status],
                'code' => $this->status
            )
        ));
    }
}