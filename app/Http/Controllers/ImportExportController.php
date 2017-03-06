<?php

namespace App\Http\Controllers;

use Exception;
use Helpers\FileHelper;
use Managers\ImportExportManager;
use Illuminate\Http\Request;



class ImportExportController extends Controller{

    private $_importExportManager;

    public function __construct(ImportExportManager $importExportManager)
    {
        $this->_importExportManager = $importExportManager;
    }

    public function exportQuestions($themeId){
        try{
            $exportFilePath = $this->_importExportManager->exportQuestions($themeId);

            return response()
                ->download($exportFilePath, 'questions_import_theme_'.$themeId)
                ->deleteFileAfterSend(true);

        } catch (Exception $exception){
            return $this->faultJSONResponse($exception->getMessage());
        }
    }

    public function importQuestions(Request $request){
        try{
            $file = $request->json('file');
            $themeId = $request->json('themeId');

            $importFilePath = ImportExportManager::$importPath.ImportExportManager::$importFileName;
            FileHelper::delete($importFilePath);

            $result = $this->_importExportManager->importQuestions($themeId, $file);
            return $this->successJSONResponse($result);
        } catch (Exception $exception){
            return $this->faultJSONResponse($exception->getMessage());
        }
    }


}