<?php

declare(strict_types=1);

use SaddlePHP\Forms\Form;
use SaddlePHP\Tests\Fixtures\RecordingField;
use Workbench\App\Models\Horse;
use Workbench\App\Saddle\HorseResource;

it('binds the model prototype into fields exactly once', function () {
    $field = RecordingField::make('name');
    $form = Form::make()->model(new Horse)->schema([$field]);

    $form->rules();
    $form->rules();
    $form->toInertia();

    expect($field->boundCalls)->toBe(1)
        ->and($field->sawPrototype)->toBeInstanceOf(Horse::class);
});

it('never binds when no model is set', function () {
    $field = RecordingField::make('name');
    Form::make()->schema([$field])->rules();

    expect($field->boundCalls)->toBe(0);
});

it('makeForm hands the resource model prototype to the form', function () {
    $form = HorseResource::makeForm();

    expect($form->prototype())->toBeInstanceOf(Horse::class);
});
