<div class="block">

    {def $rating = $attribute.content}
    <p>Level: {$rating.level}</p>
    <p>Exp: {$rating.exp}</p>
    <p>Completed: {$rating.completed}</p>

    {undef $rating}

</div>