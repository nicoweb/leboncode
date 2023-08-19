<?php

$cachedContainer = require dirname(__DIR__, 5).'/var/cache/prod/NicolasLefevre_LeBonCode_KernelProdContainer.preload.php';

if (file_exists($cachedContainer)) {
    require $cachedContainer;
}
