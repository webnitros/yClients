<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/yClients/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/yclients')) {
            $cache->deleteTree(
                $dev . 'assets/components/yclients/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/yclients/', $dev . 'assets/components/yclients');
        }
        if (!is_link($dev . 'core/components/yclients')) {
            $cache->deleteTree(
                $dev . 'core/components/yclients/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/yclients/', $dev . 'core/components/yclients');
        }
    }
}

return true;