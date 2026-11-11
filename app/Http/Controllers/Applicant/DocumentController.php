<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    private array $requiredDocuments = [
        'ktp'    => 'KTP / Kartu Identitas',
        'ijazah' => 'Ijazah / Surat Keterangan Lulus',
        'foto'   => 'Pas Foto (latar merah/biru)',
        'rapor'  => 'Rapor Semester Terakhir',
    ];

    public function index(Application $application)
    {
        $this->authorize('view', $application);

        $documents = $application->documents()->get()->keyBy('document_type');

        return view('applicant.document.index', [
            'application'       => $application,
            'requiredDocuments' => $this->requiredDocuments,
            'documents'         => $documents,
        ]);
    }

    public function store(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        if (! $application->isEditable()) {
            abort(403, 'Dokumen tidak bisa diubah pada status ini.');
        }

        $request->validate([
            'document_type' => 'required|in:' . implode(',', array_keys($this->requiredDocuments)),
            'file'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $file     = $request->file('file');
        $filename = Str::uuid() . '.' . $file->extension();
        $path     = $file->storeAs("documents/{$application->id}", $filename, 'private');

        // Hapus dokumen lama jika ada
        $existing = $application->documents()
            ->where('document_type', $request->document_type)
            ->first();

        if ($existing) {
            Storage::disk('private')->delete($existing->file_path);
            $existing->delete();
        }

        ApplicationDocument::create([
            'application_id'      => $application->id,
            'document_type'       => $request->document_type,
            'label'               => $this->requiredDocuments[$request->document_type],
            'original_name'       => $file->getClientOriginalName(),
            'file_path'           => $path,
            'mime_type'           => $file->getMimeType(),
            'file_size'           => $file->getSize(),
            'verification_status' => 'pending',
            'uploaded_at'         => now(),
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function destroy(Application $application, ApplicationDocument $document)
    {
        $this->authorize('update', $application);

        if (! $application->isEditable()) {
            abort(403);
        }

        Storage::disk('private')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(ApplicationDocument $document)
    {
        $application = $document->application;
        $this->authorize('view', $application);

        return Storage::disk('private')->download(
            $document->file_path,
            $document->original_name
        );
    }
}