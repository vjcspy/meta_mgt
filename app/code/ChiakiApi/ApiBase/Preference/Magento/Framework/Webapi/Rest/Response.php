<?php


namespace ChiakiApi\ApiBase\Preference\Magento\Framework\Webapi\Rest;


class Response extends \Magento\Framework\Webapi\Rest\Response
{
    /**
     *
     * @inheritDoc
     */
    protected function _renderMessages()
    {
        $responseHttpCode = null;
        /** @var \Exception $exception */
        foreach ($this->getException() as $exception) {
            $maskedException = $this->_errorProcessor->maskException($exception);
            $messageData = [
                'message' => $maskedException->getMessage(),
            ];
            if ($maskedException->getErrors()) {
                $messageData['errors'] = [];
                foreach ($maskedException->getErrors() as $errorMessage) {
                    $errorData['message'] = $errorMessage->getRawMessage();
                    $errorData['parameters'] = $errorMessage->getParameters();
                    $messageData['errors'][] = $errorData;
                }
            }
            if ($maskedException->getCode()) {
                $messageData['code'] = $maskedException->getCode();
            }
            if ($maskedException->getDetails()) {
                $messageData['parameters'] = $maskedException->getDetails();
            }
            if ($this->_appState->getMode() == \Magento\Framework\App\State::MODE_DEVELOPER) {
                $messageData['trace'] = $exception instanceof \Magento\Framework\Webapi\Exception
                    ? $exception->getStackTrace()
                    : $exception->getTraceAsString();
            }
            $responseHttpCode = $maskedException->getHttpCode();
        }
        // set HTTP code of the last error, Content-Type, and all rendered error messages to body
        $this->setHttpResponseCode($responseHttpCode);
        $this->setMimeType($this->_renderer->getMimeType());
        $this->setBody($this->_renderer->render(['error' => $messageData]));
        return $this;
    }
}
