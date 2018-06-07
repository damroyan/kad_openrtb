
<div class="list-container">
{% for item in items %}
    <a href="{{ item['url'] }}" target="_blank" class="list-container-item" >
        <div class="innerWrap">
            <div class="imgFrame">
                <div style="background: url({{ item['img']['url'] }}) 50% 50% no-repeat;">
                    &nbsp;
                </div>
            </div>

            <div class="title">
                {{ item['text'] }}
                <img src="{{ item['tracking_pixel'] }}" width="1" height="1" />

                {% if ( item['nurl'] ) %}
                    <img src="{{ item['nurl'] }}" width="1" height="1" />
                {% endif %}

            </div>
            <div class="clear"></div>
        </div>
    </a>
{% endfor %}
</div>