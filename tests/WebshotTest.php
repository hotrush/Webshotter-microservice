<?php

class WebshotTest extends TestCase
{
    public function testWebshotAccessKey()
    {
        $this->post('/api/webshot');

        $this->assertEquals($this->response->status(), 401);
        $this->assertEquals($this->response->getContent(), 'Unauthorized.');
    }

    public function testWebshotValidation()
    {
        $this->post('/api/webshot?key=123123123')
            ->seeJson([
                'url' => [
                    'The url field is required.'
                ]
            ]);
        $this->post('/api/webshot?key=123123123', [
            'url' => 'https://github.com',
            'extension' => 'foo',
            'width' => 'foo',
            'height' => 'foo',
            'filename' => '@#$%^&',
        ])
            ->seeJson([
                'extension' => [
                    'The selected extension is invalid.'
                ],
                'width' => [
                    'The width must be an integer.',
                ],
                'height' => [
                    'The height must be an integer.',
                ],
                'filename' => [
                    'The filename may only contain letters, numbers, and dashes.',
                ],
            ]);
    }

    /**
     *
     * @return void
     */
    public function testWebshotCreating()
    {
        $this->post('/api/webshot?key=123123123', [
            'url' => 'https://github.com',
            'extension' => 'png',
            'width' => 1200,
            'height' => 800,
            'full_page' => 0,
            'filename' => 'test',
            'path' => '2016/00/00',
        ]);

        $this->assertEquals($this->response->status(), 200);
        $this->assertEquals($this->response->headers->get('Content-Type'), 'application/json');
        $this->assertEquals(
            $this->response->getContent(),
            json_encode([
                'path' => '2016/00/00/test.png',
                'url' => 'http://localhost/webshots/2016/00/00/test.png'
            ])
        );
        $this->assertFileExists($this->app->basePath('public/webshots/2016/00/00/test.png'));

        unlink($this->app->basePath('public/webshots/2016/00/00/test.png'));
        rmdir($this->app->basePath('public/webshots/2016/00/00'));
        rmdir($this->app->basePath('public/webshots/2016/00'));
        rmdir($this->app->basePath('public/webshots/2016'));
    }

    /**
     *
     * @return void
     */
    public function testWebshotTimeout()
    {
        $this->post('/api/webshot?key=123123123', [
            'url' => 'http://httpbin.org/delay/10',
            'extension' => 'png',
            'width' => 1200,
            'height' => 800,
            'full_page' => 0,
            'filename' => 'test',
            'path' => '2016/00/00',
            'timeout' => 1
        ]);

        $this->assertEquals($this->response->status(), 500);
        $this->assertEquals($this->response->headers->get('Content-Type'), 'application/json');
        $this->assertEquals(
            $this->response->getContent(),
            json_encode([
                'message' => 'Link timeout.'
            ])
        );
    }
}
