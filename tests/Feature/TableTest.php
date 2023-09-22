<?php

use GSpataro\CLI\Helper\Table;
use GSpataro\CLI\Output;

use function GSpataro\CLI\Functions\col;
use function GSpataro\CLI\Functions\row;

uses()->group('helpers');

beforeEach(function () {
    $this->outputStream = fopen('gstest://output', 'w+');
    $this->output = new Output($this->outputStream);
    $this->table = new Table($this->output);
});

it('returns a table row', function () {
    $result = row(['foo', 'bar'], 'heading');
    expect($result)->toBe(['heading' => ['foo', 'bar']]);
});

it('returns a table column', function () {
    $result = col('test', 'heading');
    expect($result)->toBe(['heading' => 'test']);
});

it('returns a basic table', function () {
    $this->table->structure(
        row(['Name', 'Surname', 'City'], 'heading'),
        row(["Wolfgang Amadeus", "Mozart", "Vienna"]),
        row(["Ludwig", "van Beethoven", "Bonn"]),
        row(["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]),
        row(["Vincenzo", "Bellini", "Catania"])
    );
    $this->table->render();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    $expected = "\033[1mName                  \033[0m\033[1mSurname           \033[0m\033[1mCity\033[0m" . PHP_EOL;
    $expected .= "Wolfgang Amadeus      \033[0mMozart            \033[0mVienna\033[0m" . PHP_EOL;
    $expected .= "Ludwig                \033[0mvan Beethoven     \033[0mBonn\033[0m" . PHP_EOL;
    $expected .= "Sergej Vasil'Evic     \033[0mRachmaninoff      \033[0mMoscow\033[0m" . PHP_EOL;
    $expected .= "Vincenzo              \033[0mBellini           \033[0mCatania\033[0m" . PHP_EOL;
    $expected .= "\033[0m" . PHP_EOL;

    expect($result)->toEqual($expected);
});

it('returns a basic table with empty columns', function () {
    $this->table->structure(
        row(["Name", "Surname", "City"], 'heading'),
        row(["Wolfgang Amadeus", "", "Vienna"]),
        row(["Ludwig", "van Beethoven"]),
        row(["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]),
        row(["Vincenzo", "Bellini"])
    );
    $this->table->render();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    $expected = "\033[1mName                  \033[0m\033[1mSurname           \033[0m\033[1mCity\033[0m" . PHP_EOL;
    $expected .= "Wolfgang Amadeus      \033[0m                  \033[0mVienna\033[0m" . PHP_EOL;
    $expected .= "Ludwig                \033[0mvan Beethoven     \033[0m\033[0m" . PHP_EOL;
    $expected .= "Sergej Vasil'Evic     \033[0mRachmaninoff      \033[0mMoscow\033[0m" . PHP_EOL;
    $expected .= "Vincenzo              \033[0mBellini           \033[0m\033[0m" . PHP_EOL;
    $expected .= "\033[0m" . PHP_EOL;

    expect($result)->toEqual($expected);
});

it('add rows to the table', function () {
    $this->table->structure(
        row(["Name", "Surname", "City"], 'heading'),
        row(["Wolfgang Amadeus", "Mozart", "Vienna"]),
        row(["Ludwig", "van Beethoven", "Bonn"]),
        row(["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"])
    );
    $this->table->addRow(['Just', 'Another', 'Heading'], 'heading');
    $this->table->addRow(['Vincenzo', 'Bellini', 'Catania']);
    $this->table->render();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    $expected = "\033[1mName                  \033[0m\033[1mSurname           \033[0m\033[1mCity\033[0m" . PHP_EOL;
    $expected .= "Wolfgang Amadeus      \033[0mMozart            \033[0mVienna\033[0m" . PHP_EOL;
    $expected .= "Ludwig                \033[0mvan Beethoven     \033[0mBonn\033[0m" . PHP_EOL;
    $expected .= "Sergej Vasil'Evic     \033[0mRachmaninoff      \033[0mMoscow\033[0m" . PHP_EOL;
    $expected .= "\033[1mJust                  \033[0m\033[1mAnother           \033[0m\033[1mHeading\033[0m" . PHP_EOL;
    $expected .= "Vincenzo              \033[0mBellini           \033[0mCatania\033[0m" . PHP_EOL;
    $expected .= "\033[0m" . PHP_EOL;

    expect($result)->toEqual($expected);
});

it('add separators to the table', function () {
    $this->table->structure(
        row(["Name", "Surname", "City"], 'heading'),
        row(["Wolfgang Amadeus", "Mozart", "Vienna"]),
        row(["Ludwig", "van Beethoven", "Bonn"]),
        row(["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"])
    );
    $this->table->addSeparator();
    $this->table->addRow(["Vincenzo", "Bellini", "Catania"]);
    $this->table->render();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    $expected = "\033[1mName                  \033[0m\033[1mSurname           \033[0m\033[1mCity\033[0m" . PHP_EOL;
    $expected .= "Wolfgang Amadeus      \033[0mMozart            \033[0mVienna\033[0m" . PHP_EOL;
    $expected .= "Ludwig                \033[0mvan Beethoven     \033[0mBonn\033[0m" . PHP_EOL;
    $expected .= "Sergej Vasil'Evic     \033[0mRachmaninoff      \033[0mMoscow\033[0m" . PHP_EOL;
    $expected .= PHP_EOL;
    $expected .= "Vincenzo              \033[0mBellini           \033[0mCatania\033[0m" . PHP_EOL;
    $expected .= "\033[0m" . PHP_EOL;

    expect($result)->toEqual($expected);
});

it('customizes padding size', function () {
    $this->table->structure(
        row(["Name", "Surname", "City"], 'heading'),
        row(["Wolfgang Amadeus", "Mozart", "Vienna"]),
        row(["Ludwig", "van Beethoven", "Bonn"]),
        row(["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]),
        row(["Vincenzo", "Bellini", "Catania"])
    );
    $this->table->setPadding(10);
    $this->table->render();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    $expected = "\033[1mName                       \033[0m\033[1mSurname                \033[0m\033[1mCity\033[0m" . PHP_EOL;
    $expected .= "Wolfgang Amadeus           \033[0mMozart                 \033[0mVienna\033[0m" . PHP_EOL;
    $expected .= "Ludwig                     \033[0mvan Beethoven          \033[0mBonn\033[0m" . PHP_EOL;
    $expected .= "Sergej Vasil'Evic          \033[0mRachmaninoff           \033[0mMoscow\033[0m" . PHP_EOL;
    $expected .= "Vincenzo                   \033[0mBellini                \033[0mCatania\033[0m" . PHP_EOL;
    $expected .= "\033[0m" . PHP_EOL;

    expect($result)->toEqual($expected);
});

it('customizes padding character', function () {
    $this->table->structure(
        row(["Name", "Surname", "City"], 'heading'),
        row(["Wolfgang Amadeus", "Mozart", "Vienna"]),
        row(["Ludwig", "van Beethoven", "Bonn"]),
        row(["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]),
        row(["Vincenzo", "Bellini", "Catania"])
    );
    $this->table->setPaddingCharacter('.');
    $this->table->render();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    $expected = "\033[1mName..................\033[0m\033[1mSurname...........\033[0m\033[1mCity\033[0m" . PHP_EOL;
    $expected .= "Wolfgang Amadeus......\033[0mMozart............\033[0mVienna\033[0m" . PHP_EOL;
    $expected .= "Ludwig................\033[0mvan Beethoven.....\033[0mBonn\033[0m" . PHP_EOL;
    $expected .= "Sergej Vasil'Evic.....\033[0mRachmaninoff......\033[0mMoscow\033[0m" . PHP_EOL;
    $expected .= "Vincenzo..............\033[0mBellini...........\033[0mCatania\033[0m" . PHP_EOL;
    $expected .= "\033[0m" . PHP_EOL;

    expect($result)->toEqual($expected);
});

it('customizes table rows', function () {
    $this->table->setStyle('heading', '{bg_green}{bold}');
    $this->table->setStyle('row', '{bg_white}');
    $this->table->setStyle('rowAlt', '{bg_white_bright}');
    $this->table->structure(
        row(["Name", "Surname", "City"], 'heading'),
        row(["Wolfgang Amadeus", "Mozart", "Vienna"], 'rowAlt'),
        row(["Ludwig", "van Beethoven", "Bonn"]),
        row(["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"], 'rowAlt'),
        row(["Vincenzo", "Bellini", "Catania"])
    );
    $this->table->render();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    $expected = "\033[42m\033[1mName                  \033[0m\033[42m\033[1mSurname           \033[0m\033[42m\033[1mCity\033[0m" . PHP_EOL;
    $expected .= "\033[107mWolfgang Amadeus      \033[0m\033[107mMozart            \033[0m\033[107mVienna\033[0m" . PHP_EOL;
    $expected .= "\033[47mLudwig                \033[0m\033[47mvan Beethoven     \033[0m\033[47mBonn\033[0m" . PHP_EOL;
    $expected .= "\033[107mSergej Vasil'Evic     \033[0m\033[107mRachmaninoff      \033[0m\033[107mMoscow\033[0m" . PHP_EOL;
    $expected .= "\033[47mVincenzo              \033[0m\033[47mBellini           \033[0m\033[47mCatania\033[0m" . PHP_EOL;
    $expected .= "\033[0m" . PHP_EOL;

    expect($result)->toEqual($expected);
});

it('returns a table with style applied to rows and columns', function () {
    $this->table->structure(
        row(['Name', 'Surname', 'City'], 'heading'),
        row([col("Wolfgang Amadeus", 'heading'), "Mozart", "Vienna"]),
        row([col("Ludwig", 'heading'), "van Beethoven", "Bonn"]),
        row([col("Sergej Vasil'Evic", 'heading'), "Rachmaninoff", "Moscow"]),
        row([col("Vincenzo", 'heading'), "Bellini", "Catania"])
    );
    $this->table->render();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    $expected = "\033[1mName                  \033[0m\033[1mSurname           \033[0m\033[1mCity\033[0m" . PHP_EOL;
    $expected .= "\033[1mWolfgang Amadeus      \033[0mMozart            \033[0mVienna\033[0m" . PHP_EOL;
    $expected .= "\033[1mLudwig                \033[0mvan Beethoven     \033[0mBonn\033[0m" . PHP_EOL;
    $expected .= "\033[1mSergej Vasil'Evic     \033[0mRachmaninoff      \033[0mMoscow\033[0m" . PHP_EOL;
    $expected .= "\033[1mVincenzo              \033[0mBellini           \033[0mCatania\033[0m" . PHP_EOL;
    $expected .= "\033[0m" . PHP_EOL;

    expect($result)->toEqual($expected);
});

it('returns a table with a separator', function () {
    $this->table->structure(
        row(['Name', 'Surname', 'City'], 'heading'),
        [],
        row(["Wolfgang Amadeus", "Mozart", "Vienna"]),
        [],
        row(["Ludwig", "van Beethoven", "Bonn"]),
        [],
        row(["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]),
        [],
        row(["Vincenzo", "Bellini", "Catania"])
    );
    $this->table->render();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    $expected = "\033[1mName                  \033[0m\033[1mSurname           \033[0m\033[1mCity\033[0m" . PHP_EOL;
    $expected .= PHP_EOL;
    $expected .= "Wolfgang Amadeus      \033[0mMozart            \033[0mVienna\033[0m" . PHP_EOL;
    $expected .= PHP_EOL;
    $expected .= "Ludwig                \033[0mvan Beethoven     \033[0mBonn\033[0m" . PHP_EOL;
    $expected .= PHP_EOL;
    $expected .= "Sergej Vasil'Evic     \033[0mRachmaninoff      \033[0mMoscow\033[0m" . PHP_EOL;
    $expected .= PHP_EOL;
    $expected .= "Vincenzo              \033[0mBellini           \033[0mCatania\033[0m" . PHP_EOL;
    $expected .= "\033[0m" . PHP_EOL;

    expect($result)->toEqual($expected);
});
