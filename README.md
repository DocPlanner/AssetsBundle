# AssetsBundle



### Sample config
```
docplanner_assets:
    use_revisions: true
    base_host: "www.example.com/platform/"
    base_path: "%kernel.root_dir%/../web/platform/"
    types:
        style:
            assets:
                common: { src: "css/rwd-common.css" }
                extra: { src: "css/rwd-extra.css" }
            groups:
                default:
                    assets:
                        - common
                        - extra
                    default: true
                homepage:
                    assets:
                        - common
                    routes: [ "homepage" ]
        script:
            assets:
                common: { src: "js/common.js" }
            groups:
                default:
                    assets:
                        - common
                    default: true
```