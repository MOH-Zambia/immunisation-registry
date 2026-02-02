<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogViewerController extends AppBaseController
{
    /**
     * Display log viewer interface
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $logPath = storage_path('logs');
        $logFiles = [];

        if (File::exists($logPath)) {
            $files = File::files($logPath);

            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'log') {
                    $logFiles[] = [
                        'name' => $file->getFilename(),
                        'path' => $file->getPathname(),
                        'size' => $this->formatBytes($file->getSize()),
                        'modified' => date('Y-m-d H:i:s', $file->getMTime())
                    ];
                }
            }

            // Sort by modified date, newest first
            usort($logFiles, function($a, $b) {
                return strcmp($b['modified'], $a['modified']);
            });
        }

        return view('admin.log_viewer', compact('logFiles'));
    }

    /**
     * View specific log file
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|string',
            'lines' => 'nullable|integer|min:10|max:5000'
        ]);

        $fileName = basename($validated['file']);
        $logPath = storage_path('logs/' . $fileName);
        $lines = $validated['lines'] ?? 100;

        if (!File::exists($logPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Log file not found'
            ], 404);
        }

        // Security check: ensure file is within logs directory
        $realPath = realpath($logPath);
        $logsDir = realpath(storage_path('logs'));

        if (strpos($realPath, $logsDir) !== 0) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        try {
            // Read last N lines efficiently
            $file = new \SplFileObject($logPath, 'r');
            $file->seek(PHP_INT_MAX);
            $totalLines = $file->key() + 1;

            $startLine = max(0, $totalLines - $lines);
            $content = [];

            $file->seek($startLine);
            while (!$file->eof()) {
                $line = $file->current();
                if ($line !== false) {
                    $content[] = $line;
                }
                $file->next();
            }

            return response()->json([
                'success' => true,
                'file' => $fileName,
                'total_lines' => $totalLines,
                'showing_lines' => count($content),
                'content' => implode('', $content)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reading log file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download log file
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|string'
        ]);

        $fileName = basename($validated['file']);
        $logPath = storage_path('logs/' . $fileName);

        if (!File::exists($logPath)) {
            abort(404, 'Log file not found');
        }

        // Security check: ensure file is within logs directory
        $realPath = realpath($logPath);
        $logsDir = realpath(storage_path('logs'));

        if (strpos($realPath, $logsDir) !== 0) {
            abort(403, 'Access denied');
        }

        return response()->download($logPath);
    }

    /**
     * Delete log file
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|string'
        ]);

        $fileName = basename($validated['file']);
        $logPath = storage_path('logs/' . $fileName);

        if (!File::exists($logPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Log file not found'
            ], 404);
        }

        // Security check: ensure file is within logs directory
        $realPath = realpath($logPath);
        $logsDir = realpath(storage_path('logs'));

        if (strpos($realPath, $logsDir) !== 0) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        // Don't allow deleting current day's log
        if ($fileName === 'laravel-' . date('Y-m-d') . '.log') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete today\'s log file'
            ], 400);
        }

        try {
            File::delete($logPath);
            return response()->json([
                'success' => true,
                'message' => 'Log file deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting log file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all old log files (keep today's)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clear()
    {
        $logPath = storage_path('logs');
        $todayLog = 'laravel-' . date('Y-m-d') . '.log';
        $deletedCount = 0;

        try {
            $files = File::files($logPath);

            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'log' &&
                    $file->getFilename() !== $todayLog &&
                    $file->getFilename() !== '.gitignore') {
                    File::delete($file->getPathname());
                    $deletedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Deleted {$deletedCount} log file(s)"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @return string
     */
    private function formatBytes($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
