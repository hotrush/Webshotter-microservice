<?php

class PingTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function testPing()
    {
        $this->get('/api/ping');

        $this->assertEquals($this->response->status(), 200);
        $this->assertEquals($this->response->headers->get('Content-Type'), 'application/json');

        $this->assertEquals(
            $this->response->getContent(), json_encode('pong')
        );
    }
}
