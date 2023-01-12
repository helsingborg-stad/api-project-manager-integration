<div class="section u-py-7 u-bt-1">
    <div class="o-container">
        <div class="o-grid">
            @includeWhen(!empty($platform['contacts']), 'partials.platform.contacts')
            @includeWhen(!empty($platform['files']), 'partials.platform.files')
            @includeWhen(!empty($platform['links']), 'partials.platform.links')
        </div>
    </div>
</div>
