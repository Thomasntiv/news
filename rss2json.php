<?php
$rss_sources = [
    "https://www.kavalapost.gr/feed/" => 1,
    "https://www.proininews.gr/feed/" => 1,
    "https://www.k-tipos.gr/feed/" => 1,
    "https://www.kavala-portal.gr/feed/" => 1,
    "https://feeds.feedburner.com/dikaiologitika/ZteV" => 1,
    "https://www.documentonews.gr/rss" => 1,
    "https://www.protothema.gr/rss" => 1,
    "https://www.tovima.gr/feed/" => 2,
    "https://www.gazzetta.gr/rss" => 2,
    "https://eleftherostypos.gr/feed" => 3,
    "https://www.makeleio.gr/feed/" => 2,
    "https://www.tanea.gr/feed/" => 3,
    "https://www.zougla.gr/feed/" => 4,
    "https://www.ethnos.gr/rss" => 2
];

$json = ["items" => []];

foreach ($rss_sources as $rss_url => $limit) {
    $rss = @simplexml_load_file($rss_url);
    if (!$rss) continue;

    $count = 0;
    foreach ($rss->channel->item as $item) {
        if ($count >= $limit) break;

        $media = $item->children('media', true);
        $image = isset($media->content) ? (string)$media->content->attributes()->url : "";

        if (!$image) {
            preg_match('/<img.*?src=["\'](.*?)["\']/', (string)$item->description, $matches);
            $image = $matches[1] ?? "https://via.placeholder.com/600x300?text=No+Image";
        }

        $json["items"][] = [
            "title" => (string)$item->title,
            "description" => strip_tags((string)$item->description),
            "link" => (string)$item->link,
            "image" => $image,
            "source" => parse_url((string)$item->link, PHP_URL_HOST)
        ];

        $count++;
    }
}

file_put_contents("json/ipiros.json", json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
echo "OK: ipiros.json updated.";
?>
