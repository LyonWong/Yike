<?php namespace Admin;?>
<div class="top-menu">
    <ul class="nav navbar-nav pull-right">
        <li class="dropdown dropdown-user">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<span class="username username-hide-on-mobile"><?=servSession::$live->get('name')?></span>
                    <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-default">
                <li>
                    <a href="/sign-out">Sign Out</a>
                </li>
            </ul>
        </li>
        <li class="dropdown dropdown-language">
            <?=wdgtLang::switchDropdown()?>
        </li>
    </ul>
</div>
