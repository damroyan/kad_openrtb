
<ul class="list-container">
{% for item in items %}
    <li class="list-container-item">
        <a class="imgFrame" target="_blank">
            <img src="{{ item['img']['url'] }}" width="250" class="image">
        </a>
        <a href="{{ item['url'] }}" class="title" target="_blank">{{ item['text'] }}</a>
        <img src="{{ item['tracking_pixel'] }}" />
    </li>
{% endfor %}
</ul>