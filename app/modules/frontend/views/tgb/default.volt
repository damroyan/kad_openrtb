
<ul>
{% for item in items %}
    <li>
        <img src="{{ item['img']['url'] }}" width="250">
        <a href="{{ item['url'] }}">{{ item['text'] }}</a>
        <img src="{{ item['tracking_pixel'] }}" />
    </li>
{% endfor %}
</ul>