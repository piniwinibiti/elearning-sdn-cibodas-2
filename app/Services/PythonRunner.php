<?php

namespace App\Services;

/**
 * PythonRunner - menjalankan script Python dengan aman di Windows.
 *
 * Masalah: Illuminate\Process::run() dapat hang di Windows karena
 * deadlock pada pipe stdout/stderr. Solusi: gunakan proc_open() dengan
 * drain kedua pipe secara manual.
 */
class PythonRunner
{
    /**
     * Jalankan script Python dan kembalikan output JSON yang sudah di-decode.
     *
     * @param  string       $scriptPath  Path absolut ke script .py
     * @param  array        $args        Argumen tambahan (path file, dll.)
     * @param  int          $timeout     Batas waktu detik (default 30)
     * @return array|null                Array hasil decode JSON, atau null jika gagal
     */
    public static function run(string $scriptPath, array $args = [], int $timeout = 30): ?array
    {
        $pythonBin = trim(env('PYTHON_PATH', 'python'));

        // Bungkus semua path dengan kutip untuk handle spasi
        $quotedArgs = array_map(fn($a) => "\"{$a}\"", $args);
        $cmd = "\"{$pythonBin}\" \"{$scriptPath}\"" . (count($quotedArgs) ? ' ' . implode(' ', $quotedArgs) : '');

        $descriptors = [
            0 => ['pipe', 'r'],   // stdin
            1 => ['pipe', 'w'],   // stdout
            2 => ['pipe', 'w'],   // stderr
        ];

        $process = proc_open($cmd, $descriptors, $pipes);

        if (!is_resource($process)) {
            \Log::error("[PythonRunner] proc_open gagal untuk: {$cmd}");
            return null;
        }

        // Tutup stdin segera
        fclose($pipes[0]);

        // Set non-blocking agar tidak deadlock
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $stdout = '';
        $stderr = '';
        $start  = time();

        // Drain loop — baca stdout & stderr sampai proses selesai atau timeout
        while (true) {
            $chunk1 = fread($pipes[1], 8192);
            $chunk2 = fread($pipes[2], 8192);

            if ($chunk1 !== false && $chunk1 !== '') $stdout .= $chunk1;
            if ($chunk2 !== false && $chunk2 !== '') $stderr .= $chunk2;

            $status = proc_get_status($process);
            if (!$status['running']) break;

            if ((time() - $start) >= $timeout) {
                proc_terminate($process);
                \Log::error("[PythonRunner] Timeout ({$timeout}s) untuk: {$cmd}");
                break;
            }

            usleep(50000); // 50ms
        }

        // Flush sisa output setelah proses berhenti
        $stdout .= stream_get_contents($pipes[1]);
        $stderr .= stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        if (!empty($stderr)) {
            \Log::warning("[PythonRunner] stderr: {$stderr}");
        }

        $decoded = json_decode(trim($stdout), true);

        if ($decoded === null && !empty($stdout)) {
            \Log::error("[PythonRunner] Output bukan JSON: {$stdout}");
        }

        return $decoded;
    }

    /**
     * Jalankan script Python dan kembalikan output raw text.
     */
    public static function runRaw(string $scriptPath, array $args = [], int $timeout = 60): ?string
    {
        $pythonBin = trim(env('PYTHON_PATH', 'python'));
        $quotedArgs = array_map(fn($a) => "\"{$a}\"", $args);
        $cmd = "\"{$pythonBin}\" \"{$scriptPath}\"" . (count($quotedArgs) ? ' ' . implode(' ', $quotedArgs) : '');

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($cmd, $descriptors, $pipes);
        if (!is_resource($process)) {
            return null;
        }

        fclose($pipes[0]);
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $stdout = '';
        $stderr = '';
        $start  = time();

        while (true) {
            $chunk1 = fread($pipes[1], 8192);
            $chunk2 = fread($pipes[2], 8192);
            if ($chunk1 !== false && $chunk1 !== '') $stdout .= $chunk1;
            if ($chunk2 !== false && $chunk2 !== '') $stderr .= $chunk2;

            $status = proc_get_status($process);
            if (!$status['running']) break;

            if ((time() - $start) >= $timeout) {
                proc_terminate($process);
                break;
            }
            usleep(50000);
        }

        $stdout .= stream_get_contents($pipes[1]);
        $stderr .= stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        return trim($stdout) !== '' ? trim($stdout) : (trim($stderr) !== '' ? trim($stderr) : null);
    }
}
