<?php
declare(strict_types=1);
namespace gtbabel\core;

use PHPUnit\Framework\TestCase;
use vielhuber\dbhelper\dbhelper;

final class DataCacheTest extends TestCase
{
    public function testPreloadPreservesCachesAcrossBatches(): void
    {
        $data = new Data();
        $data->table = 'translations';
        $data->db = new class extends dbhelper {
            public array $batchSizes = [];

            public function fetch_all(string $query, mixed ...$params)
            {
                $rows = parent::fetch_all($query, ...$params);
                $this->batchSizes[] = count($rows);
                return $rows;
            }
        };
        $data->db->connect('pdo', 'sqlite', ':memory:');
        $data->db->query(
            'CREATE TABLE translations (id INTEGER PRIMARY KEY, str TEXT, trans TEXT, lng_source TEXT,
                lng_target TEXT, context TEXT, checked INTEGER)'
        );
        $expected = ['cache' => [], 'cache_reverse' => [], 'checked_strings' => [], 'save' => []];
        for ($index = 1; $index <= 2001; $index++) {
            $source = 'Source ' . $index;
            $translation = 'Translation ' . $index;
            $language = $index % 2 === 0 ? 'fr' : 'en';
            $context = $index % 3 === 0 ? 'slug' : '';
            if (in_array($index, [1, 1001, 2001], true)) {
                $source = $index === 1 ? 'A &amp; B' : ($index === 1001 ? 'A & B' : 'Other');
                $translation = $index === 1 ? 'First' : ($index === 1001 ? 'C &amp; D' : 'C & D');
                $context = 'slug';
            }
            $checked = $index === 1 ? 0 : 1;
            $data->db->query(
                'INSERT INTO translations VALUES (?, ?, ?, ?, ?, ?, ?)',
                $index * 2,
                $source,
                $translation,
                'de',
                $language,
                $context,
                $checked
            );
            $expected['cache']['de'][$language][$context][html_entity_decode($source)] = $translation;
            $expected['cache_reverse']['de'][$language][$context][html_entity_decode($translation)] = $source;
            $expected['checked_strings']['de'][$language][$context][html_entity_decode($source)] = $checked === 1;
        }

        $data->preloadDataInCache();

        $this->assertSame($expected, $data->data);
        $this->assertSame([1000, 1000, 1], $data->db->batchSizes);
        $this->assertSame('C &amp; D', $data->data['cache']['de']['en']['slug']['A & B']);
        $this->assertSame('Other', $data->data['cache_reverse']['de']['en']['slug']['C & D']);
        $this->assertTrue($data->data['checked_strings']['de']['en']['slug']['A & B']);

        $data->db->query('DELETE FROM translations WHERE id > 4000');
        $data->db->batchSizes = [];
        $data->preloadDataInCache();
        $this->assertSame([1000, 1000, 0], $data->db->batchSizes);
        $this->assertArrayNotHasKey('Other', $data->data['cache']['de']['en']['slug']);

        $data->db->query('DELETE FROM translations');
        $data->preloadDataInCache();
        $this->assertSame(['cache' => [], 'cache_reverse' => [], 'checked_strings' => [], 'save' => []], $data->data);
    }

    public function testPreloadWithoutDatabase(): void
    {
        $data = new Data();
        $data->preloadDataInCache();
        $this->assertSame(['cache' => [], 'cache_reverse' => [], 'checked_strings' => [], 'save' => []], $data->data);
    }
}
