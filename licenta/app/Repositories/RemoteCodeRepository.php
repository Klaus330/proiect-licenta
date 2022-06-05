<?php

namespace App\Repositories;

use App\Models\RemoteCode;
use App\Models\Scheduler;

class RemoteCodeRepository {

    public function saveRemoteCodeForScheduler(Scheduler $scheduler, $remoteCodeFile) {
        
        if(!is_dir($scheduler->remote_code_path)) {
            mkdir($scheduler->remote_code_path);
        }
        
        $payload = [
            'scheduler_id' => $scheduler->id,
            'filename' => $remoteCodeFile->getClientOriginalName(),
            'path' => $scheduler->remote_code_path_without_base_path,
            'language' => $remoteCodeFile->getClientOriginalExtension() === "txt" ? "javascript" : $remoteCodeFile->getClientOriginalExtension(),
        ];

        if ($scheduler->has_remote_code) {
            unset($payload['scheduler_id']);
            $scheduler->remote_code_file->update($payload);
        } else {
            RemoteCode::create($payload);
        }

        $remoteCodeFile->move($scheduler->remote_code_path, $remoteCodeFile->getClientOriginalName());

        $scheduler->update([
            'has_remote_code' => true,
            'needsAuth' => false,
        ]);
    }

}