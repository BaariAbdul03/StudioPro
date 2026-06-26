const fs = require('fs');

function extractVueComponent(sourceHtml, targetVue, name) {
    let html = fs.readFileSync(sourceHtml, 'utf8');
    let bodyMatch = html.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
    let content = bodyMatch ? bodyMatch[1] : '';
    
    // remove inline scripts
    content = content.replace(/<script>[\s\S]*?<\/script>/gi, '');
    
    let vueTemplate = `<template>\n<div class="${name.toLowerCase()} font-geist relative">\n${content}\n</div>\n</template>\n\n<script setup>\n</script>\n`;
    
    fs.writeFileSync(targetVue, vueTemplate);
}

extractVueComponent('D:/PageBuilder/stitch_advanced_visual_page_builder/Phase 3 Designs/product_inventory_manager/code.html', 'D:/PageBuilder/frontend/src/views/InventoryManager.vue', 'InventoryManager');
extractVueComponent('D:/PageBuilder/stitch_advanced_visual_page_builder/Phase 3 Designs/visual_editor_shopping_cart_drawer_preview/code.html', 'D:/PageBuilder/frontend/src/components/editor/CartDrawer.vue', 'CartDrawer');
