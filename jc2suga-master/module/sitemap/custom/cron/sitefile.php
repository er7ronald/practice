<?php
// サイトマップファイル作成
cron_master::setCron('sitemapUpdate','SitefileCron','sitefile');

class SitefileCron{
    function sitefile(){
        try {
            SitemapLogic::create();
        } catch(Exception $e) {
            SystemUtil::dumper($e->getMessage());
            SystemUtil::dumper($e->getTraceAsString());
        }
    }
}

?>
<!-- /cron.php?label=sitemapUpdate&class=SitefileCron&method=sitefile -->
<!-- http://localhost:8888/jc2suga-master/cron.php?label=sitemapUpdate&class=CountCron&method=sitefile -->
