<?php

namespace App\Http\Controllers;

use hotrush\Webshotter\Exception\TimeoutException;
use Illuminate\Http\Request;
use hotrush\Webshotter\Webshot;
use Illuminate\Support\Facades\Storage;

class WebshotController extends Controller
{
    /**
     * Create a webshot by link
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'url' => 'required|url',
            'extension' => 'in:jpg,png,pdf',
            'width' => 'integer',
            'height' => 'integer',
            'full_page' => 'boolean',
            'filename' => 'alpha_dash',
            'timeout' => 'integer',
            'path' => 'string',
        ]);

        $filename = $request->input('filename', sha1($request->input('url')));
        $fullPath = trim(
                str_replace(
                    ' ',
                    '-',
                    $request->input('path', date('Y/m/d'))
                ),
                '/'
            ).'/'.$filename.'.'.$request->input('extension', 'jpg');
        $tmpPath = storage_path('app');

        $webshot = new Webshot(env('PHANTOM_JS_BIN'));

        try {

            $tmpFile = $webshot
                ->setUrl($request->input('url'))
                ->setWidth($request->input('width', 1200))
                ->setHeight($request->input('height', 800))
                ->setFullPage($request->input('full_page', false))
                ->setTimeout($request->input('timeout', 30))
                ->{'saveTo'.ucfirst($request->input('extension', 'jpg'))}(
                    $filename,
                    $tmpPath
                );

            // Put file to it's destination
            Storage::put(
                $fullPath,
                file_get_contents($tmpFile)
            );

            unlink($tmpFile);

            return response()->json([
                'path' => $fullPath,
                'url' => app('filesystemPublicUrl')->publicUrl(null, $fullPath),
            ]);

        } catch (TimeoutException $e) {

            return response()->json([
                'message' => 'Link timeout.',
            ], 500);

        }

    }
}
















