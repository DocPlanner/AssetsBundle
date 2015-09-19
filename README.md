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


### Sample use in twig
```
{% for style in assets_style() %}<link href="{{ style }}" rel="stylesheet" />{% endfor %}
<style type="text/css">{% for style in assets_style(true) %}{{ style }}{% endfor %}</style>

{% for script in assets_script() %}<script src="{{ script }}" type="text/javascript"></script>{% endfor %}
<script type="text/javascript">{% for script in assets_script(true) %}{{ script }}{% endfor %}</script>

```