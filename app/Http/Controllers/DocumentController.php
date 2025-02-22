<?php
namespace App\Http\Controllers;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\Project;

class DocumentController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'titlefile' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,docx,jpg,png,jpeg,gif|max:10240', // Exemple de validation
        ]);

        // Sauvegarde du document
        $document = new Document();
        $document->project_id = $project->id;
        $document->title = $request->titlefile;
        $document->file_path = $request->file('file')->store('documents', 'public');

        $document->save();

        return back()->with('success', 'Document ajouté avec succès');
    }

    public function destroy(Project $project, Document $document)
    {
        $document->delete();

        return back()->with('success', 'Document supprimé avec succès');
    }
}
