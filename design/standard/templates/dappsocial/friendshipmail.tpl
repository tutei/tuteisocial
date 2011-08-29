{set-block scope=root variable=subject}{ezini('SiteSettings', 'SiteName', 'site.ini')} - New Friendship Request{/set-block}
{set-block scope=root variable=message}
<p>Ol&aacute; {$receiver.contentobject.name}</p>
<p>O usuário <a href="{concat('http://', ezini('SiteSettings', 'SiteURL', 'site.ini')|explode('/index.php')[0],'/',$sender.contentobject.main_node.url_alias)}">{$sender.contentobject.name}</a> quer ser seu amigo.</p>
<p>Veja a mensagem que ele deixou:</p>
<p><i>{$user_message}</i></p>
<p><a href="{concat('http://', ezini('SiteSettings', 'SiteURL', 'site.ini')|explode('/index.php')[0])}">{ezini('SiteSettings', 'SiteName', 'site.ini')}</a></p>
{/set-block}