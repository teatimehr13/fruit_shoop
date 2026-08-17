<?php

namespace App\Filament\Pages;

use App\Models\About;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Facades\Storage;

class ManageAbout extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $navigationLabel = '關於我們';

    protected static ?string $title = '關於我們';

    protected static string $view = 'filament.pages.manage-about';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(About::first()?->only(['title', 'content', 'image']) ?? []);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('標題')
                    ->maxLength(255),
                TiptapEditor::make('content')
                    ->label('內容')
                    ->tools(['bold', 'italic', 'underline', 'lead', 'small', 'bullet-list', 'ordered-list', 'hr', 'link', 'oembed', '|', 'color', 'highlight']),
                FileUpload::make('image')
                    ->label('圖片')
                    ->disk('public')
                    ->directory('about')
                    ->image()
                    ->imageEditor()
                    ->openable()
                    ->deletable()
                    // 用相對路徑取代 Storage::url() 產生的絕對網址,避免開發環境 APP_URL
                    // 跟瀏覽器實際連進來的 host/port 對不上導致預覽卡在 loading
                    ->getUploadedFileUsing(function (BaseFileUpload $component, string $file, string | array | null $storedFileNames) {
                        $storage = $component->getDisk();

                        if (! $storage->exists($file)) {
                            return null;
                        }

                        return [
                            'name' => $storedFileNames ?? basename($file),
                            'size' => $storage->size($file),
                            'type' => $storage->mimeType($file),
                            'url' => '/storage/' . $file,
                        ];
                    }),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $record = About::first();
        $oldPath = $record?->image;
        $newPath = $data['image'] ?? null;

        $record ? $record->update($data) : About::create($data);

        if ($oldPath && $oldPath !== $newPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        Notification::make()
            ->title('已儲存')
            ->success()
            ->send();
    }
}
