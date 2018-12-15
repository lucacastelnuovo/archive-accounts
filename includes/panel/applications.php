<?php

function applications_list($user_id)
{
    echo '<style>.collection .collection-item.avatar{min-height:0;}</style>';
    echo '<ul class="collection">';

    $user_applications = json_decode(sql_select('users', 'applications', "id='{$user_id}'", true)['applications']);

    foreach ($user_applications as $user_application) {
        $client = sql_select('clients', 'redirect_url,user_id,name,logo_url,description,suspended', "client_id='{$user_application}'", true);
        $owner = sql_select('users', 'username', "id='{$client['user_id']}'", true);

        echo <<<HTML
        <li class="collection-item avatar">
            <a href="?application={$user_application}">
                <img class="circle" src="{$client['logo_url']}" onerror="this.src='https://github.com/identicons/{$user_application}.png'"> <span class="title">{$client['name']}</span>
            </a>
                <p>Owned by <span class="blue-text">{$owner['username']}</span></p>
        </li>
HTML;
    }

    echo '</ul>';
}

function application_info($client_id)
{
    $client = clean_data($client_id);
    $client = sql_select('clients', 'redirect_url,user_id,name,logo_url,description,suspended', "client_id='{$client_id}'", true);

    $CSRFtoken = csrf_gen();

    echo <<<HTML
    <div class="row">
        <div class="col s4">
            <img class="responsive-img" src="{$client['logo_url']}" onerror="this.src='https://github.com/identicons/{$client_id}.png'" width="75">
        </div>
        <div class="col s8">
            <h4>
                {$client['name']}
            </h4>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col s8">
            <h4>
                Permissions
            </h4>
        </div>
        <div class="col s4">
            <a href="?revoke={$client_id}&CSRFtoken={$CSRFtoken}" class="btn waves-effect blue accent-4" onclick="return confirm('Are you sure?')">Revoke Access</a>
        </div>
    </div>
    <div class="row">
        <ul class="browser-default">
            <li>get scopes</li>
            <li>query scopes</li>
            <li>display description</li>
        </ul>
    </div>
HTML;
}

function application_revoke($client_id, $CSRFtoken)
{
    //remove app from user
    //delete all authorization codes with client_id
    //delete all access_tokens with client_id
}
