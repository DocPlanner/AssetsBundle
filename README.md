# AssetsBundle



### Sample config
```
docplanner_assets:
    base:
        host: "assets.example.com"
        path: "/"
    style:
        assets:
            common: { src: "/to/some/file.css" }
            fries:  { src: "/to/some/other/file.css", inline: true }
        groups:
            default:
                assets:
                    - common
                    - fries
                default: true
            homepage:
                assets:
                    - common
                    - fries
                    - homepage
                routes: [ "homepage", "homepage_with_fireworks" ]
    script:
        assets:
            common: { src: "/to/some/file.js" }
            fries:  { src: "/to/some/other/file.js", inline: true }
        groups:
            default:
                assets:
                    - common
                    - fries
                default: true
            homepage:
                assets:
                    - common
                    - fries
                    - homepage
                routes: [ "homepage", "homepage_with_fireworks" ]
```