<?php

use GSpataro\CLI\Helper\Table;
use GSpataro\CLI\Output;

beforeEach(function () {
    $this->output = new Output();
    $this->table = new Table($this->output);
});

it('returns a basic table', function () {
    $structure = [
        ["heading" => ["Name", "Surname", "City"]],
        ["row" => ["Wolfgang Amadeus", "Mozart", "Vienna"]],
        ["row" => ["Ludwig", "van Beethoven", "Bonn"]],
        ["row" => ["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]],
        ["row" => ["Vincenzo", "Bellini", "Catania"]]
    ];

    $this->table->setRows($structure);
    $result = $this->table->build();

    $expected = "{bold}Name                  {clear}{bold}Surname           {clear}{bold}City{clear}{nl}";
    $expected .= "Wolfgang Amadeus      {clear}Mozart            {clear}Vienna{clear}{nl}";
    $expected .= "Ludwig                {clear}van Beethoven     {clear}Bonn{clear}{nl}";
    $expected .= "Sergej Vasil'Evic     {clear}Rachmaninoff      {clear}Moscow{clear}{nl}";
    $expected .= "Vincenzo              {clear}Bellini           {clear}Catania{clear}{nl}";

    expect($result)->toEqual($expected);
});

it('returns a basic table with empty columns', function () {
    $structure = [
        ["heading" => ["Name", "Surname", "City"]],
        ["row" => ["Wolfgang Amadeus", "", "Vienna"]],
        ["row" => ["Ludwig", "van Beethoven"]],
        ["row" => ["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]],
        ["row" => ["Vincenzo", "Bellini"]]
    ];

    $this->table->setRows($structure);
    $result = $this->table->build();

    $expected = "{bold}Name                  {clear}{bold}Surname           {clear}{bold}City{clear}{nl}";
    $expected .= "Wolfgang Amadeus      {clear}                  {clear}Vienna{clear}{nl}";
    $expected .= "Ludwig                {clear}van Beethoven     {clear}{clear}{nl}";
    $expected .= "Sergej Vasil'Evic     {clear}Rachmaninoff      {clear}Moscow{clear}{nl}";
    $expected .= "Vincenzo              {clear}Bellini           {clear}{clear}{nl}";

    expect($result)->toEqual($expected);
});

it('add rows to the table', function () {
    $structure = [
        ["heading" => ["Name", "Surname", "City"]],
        ["row" => ["Wolfgang Amadeus", "Mozart", "Vienna"]],
        ["row" => ["Ludwig", "van Beethoven", "Bonn"]],
        ["row" => ["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]]
    ];

    $this->table->setRows($structure);
    $this->table->addRow(['Just', 'Another', 'Heading'], 'heading');
    $this->table->addRow(['Vincenzo', 'Bellini', 'Catania']);
    $result = $this->table->build();

    $expected = "{bold}Name                  {clear}{bold}Surname           {clear}{bold}City{clear}{nl}";
    $expected .= "Wolfgang Amadeus      {clear}Mozart            {clear}Vienna{clear}{nl}";
    $expected .= "Ludwig                {clear}van Beethoven     {clear}Bonn{clear}{nl}";
    $expected .= "Sergej Vasil'Evic     {clear}Rachmaninoff      {clear}Moscow{clear}{nl}";
    $expected .= "{bold}Just                  {clear}{bold}Another           {clear}{bold}Heading{clear}{nl}";
    $expected .= "Vincenzo              {clear}Bellini           {clear}Catania{clear}{nl}";

    expect($result)->toEqual($expected);
});

it('add separators to the table', function () {
    $structure = [
        ["heading" => ["Name", "Surname", "City"]],
        ["row" => ["Wolfgang Amadeus", "Mozart", "Vienna"]],
        ["row" => ["Ludwig", "van Beethoven", "Bonn"]],
        ["row" => ["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]]
    ];

    $this->table->setRows($structure);
    $this->table->addSeparator();
    $this->table->addRow(["Vincenzo", "Bellini", "Catania"]);
    $result = $this->table->build();

    $expected = "{bold}Name                  {clear}{bold}Surname           {clear}{bold}City{clear}{nl}";
    $expected .= "Wolfgang Amadeus      {clear}Mozart            {clear}Vienna{clear}{nl}";
    $expected .= "Ludwig                {clear}van Beethoven     {clear}Bonn{clear}{nl}";
    $expected .= "Sergej Vasil'Evic     {clear}Rachmaninoff      {clear}Moscow{clear}{nl}";
    $expected .= "{nl}";
    $expected .= "Vincenzo              {clear}Bellini           {clear}Catania{clear}{nl}";

    expect($result)->toEqual($expected);
});

it('customizes padding size', function () {
    $structure = [
        ["heading" => ["Name", "Surname", "City"]],
        ["row" => ["Wolfgang Amadeus", "Mozart", "Vienna"]],
        ["row" => ["Ludwig", "van Beethoven", "Bonn"]],
        ["row" => ["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]],
        ["row" => ["Vincenzo", "Bellini", "Catania"]]
    ];

    $this->table->setRows($structure);
    $this->table->setPadding(10);
    $result = $this->table->build();

    $expected = "{bold}Name                       {clear}{bold}Surname                {clear}{bold}City{clear}{nl}";
    $expected .= "Wolfgang Amadeus           {clear}Mozart                 {clear}Vienna{clear}{nl}";
    $expected .= "Ludwig                     {clear}van Beethoven          {clear}Bonn{clear}{nl}";
    $expected .= "Sergej Vasil'Evic          {clear}Rachmaninoff           {clear}Moscow{clear}{nl}";
    $expected .= "Vincenzo                   {clear}Bellini                {clear}Catania{clear}{nl}";

    expect($result)->toEqual($expected);
});

it('customizes padding character', function () {
    $structure = [
        ["heading" => ["Name", "Surname", "City"]],
        ["row" => ["Wolfgang Amadeus", "Mozart", "Vienna"]],
        ["row" => ["Ludwig", "van Beethoven", "Bonn"]],
        ["row" => ["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]],
        ["row" => ["Vincenzo", "Bellini", "Catania"]]
    ];

    $this->table->setRows($structure);
    $this->table->setPaddingCharacter('.');
    $result = $this->table->build();

    $expected = "{bold}Name..................{clear}{bold}Surname...........{clear}{bold}City{clear}{nl}";
    $expected .= "Wolfgang Amadeus......{clear}Mozart............{clear}Vienna{clear}{nl}";
    $expected .= "Ludwig................{clear}van Beethoven.....{clear}Bonn{clear}{nl}";
    $expected .= "Sergej Vasil'Evic.....{clear}Rachmaninoff......{clear}Moscow{clear}{nl}";
    $expected .= "Vincenzo..............{clear}Bellini...........{clear}Catania{clear}{nl}";

    expect($result)->toEqual($expected);
});

it('customizes table rows', function () {
    $structure = [
        ["heading" => ["Name", "Surname", "City"]],
        ["rowAlt" => ["Wolfgang Amadeus", "Mozart", "Vienna"]],
        ["row" => ["Ludwig", "van Beethoven", "Bonn"]],
        ["rowAlt" => ["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]],
        ["row" => ["Vincenzo", "Bellini", "Catania"]]
    ];

    $this->table->setStyle('heading', '{bg_green}{bold}');
    $this->table->setStyle('row', '{bg_white}');
    $this->table->setStyle('rowAlt', '{bg_white_bright}');
    $this->table->setRows($structure);
    $result = $this->table->build();

    $expected = "{bg_green}{bold}Name                  {clear}{bg_green}{bold}Surname           {clear}{bg_green}{bold}City{clear}{nl}";
    $expected .= "{bg_white_bright}Wolfgang Amadeus      {clear}{bg_white_bright}Mozart            {clear}{bg_white_bright}Vienna{clear}{nl}";
    $expected .= "{bg_white}Ludwig                {clear}{bg_white}van Beethoven     {clear}{bg_white}Bonn{clear}{nl}";
    $expected .= "{bg_white_bright}Sergej Vasil'Evic     {clear}{bg_white_bright}Rachmaninoff      {clear}{bg_white_bright}Moscow{clear}{nl}";
    $expected .= "{bg_white}Vincenzo              {clear}{bg_white}Bellini           {clear}{bg_white}Catania{clear}{nl}";

    expect($result)->toEqual($expected);
});
