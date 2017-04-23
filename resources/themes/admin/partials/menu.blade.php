<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">@lang('labels.content')</li>
            @if ($user->hasAccess('page.read'))
                <li class="{!! active_class('admin.page*') !!}">
                    <a href="{!! route('admin.page.index') !!}">
                        <i class="fa fa-file-text"></i>
                        <span>@lang('labels.pages')</span>

                        @if ($user->hasAccess('page.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_page')"
                                   data-href="{!! route('admin.page.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('news.read'))
                <li class="{!! active_class('admin.news*') !!}">
                    <a href="{!! route('admin.news.index') !!}">
                        <i class="fa fa-newspaper-o"></i>
                        <span>@lang('labels.news')</span>

                        @if ($user->hasAccess('news.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_news')"
                                   data-href="{!! route('admin.news.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('menu.read'))
                <li class="{!! active_class('admin.menu*') !!}">
                    <a href="{!! route('admin.menu.index') !!}">
                        <i class="fa fa-bars"></i>
                        <span>@lang('labels.menus')</span>

                        @if ($user->hasAccess('menu.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_menu')"
                                   data-href="{!! route('admin.menu.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('textwidget.read'))
                <li class="{!! active_class('admin.text_widget*') !!}">
                    <a href="{!! route('admin.text_widget.index') !!}">
                        <i class="fa fa-font"></i>
                        <span>@lang('labels.text_widgets')</span>

                        @if ($user->hasAccess('textwidget.create'))
                            <small class="label create-label pull-right bg-green"
                                   title="@lang('labels.add_text_widget')"
                                   data-href="{!! route('admin.text_widget.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
	    @endif

	     @if ($user->hasAccess('productable.read'))
                <li class="{!! active_class('admin.productable*') !!}">
                    <a href="{!! route('admin.productable.index') !!}">
                        <i class="fa fa-bookmark-o"></i>
                        <span>@lang('labels.productables')</span>

                        @if ($user->hasAccess('productable.create'))
                            <small class="label create-label pull-right bg-green"
                                   title="@lang('labels.add_productable')"
                                   data-href="{!! route('admin.productable.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
	    @endif

            @if ($user->hasAccess('mainpagemenu.read'))
                <li class="{!! active_class('admin.mainpagemenu*') !!}">
                    <a href="{!! route('admin.mainpagemenu.index') !!}">
                        <i class="fa fa-building-o"></i>
                        <span>@lang('labels.mainpagemenus')</span>

                        @if ($user->hasAccess('mainpagemenu.create'))
                            <small class="label create-label pull-right bg-green"
                                   title="@lang('labels.add_mainpagemenu')"
                                   data-href="{!! route('admin.mainpagemenu.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif



            @if ($user->hasAccess('variablevalue.read'))
                <li class="{!! active_class('admin.variable*') !!}">
                    <a href="{!! route('admin.variable.value.index') !!}">
                        <i class="fa fa-cog"></i>
                        <span>@lang('labels.variables')</span>
                    </a>
                </li>
            @endif

            <li class="header">@lang('labels.flowers')</li>


            @if ($user->hasAccess('flower.read'))
                <li class="{!! active_class('admin.flower*') !!}">
                    <a href="{!! route('admin.flower.index') !!}">
                        <i class="fa fa-leaf"></i>
                        <span>@lang('labels.flowers')</span>

                        @if ($user->hasAccess('flower.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_flower')"
                                   data-href="{!! route('admin.flower.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('box.read'))
                <li class="{!! active_class('admin.box*') !!}">
                    <a href="{!! route('admin.box.index') !!}">
                        <i class="fa fa-cubes"></i>
                        <span>@lang('labels.boxes')</span>

                        @if ($user->hasAccess('box.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_box')"
                                   data-href="{!! route('admin.box.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('color.read'))
                <li class="{!! active_class('admin.color*') !!}">
                    <a href="{!! route('admin.color.index') !!}">
                        <i class="fa fa-paint-brush"></i>
                        <span>@lang('labels.colors')</span>

                        @if ($user->hasAccess('color.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_color')"
                                   data-href="{!! route('admin.color.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('category.read'))
                <li class="{!! active_class('admin.category*') !!}">
                    <a href="{!! route('admin.category.index') !!}">
                        <i class="fa fa-files-o"></i>
                        <span>@lang('labels.categories')</span>

                        @if ($user->hasAccess('category.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_category')"
                                   data-href="{!! route('admin.category.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            <li class="header">@lang('labels.market')</li>

            @if ($user->hasAccess('order.read'))
                <li class="{!! active_class('admin.order*') !!}">
                    <a href="{!! route('admin.order.index') !!}">
                        <i class="fa fa-shopping-cart"></i>
                        <span>@lang('labels.orders')</span>

                        @if ($user->hasAccess('order.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_order')"
                                   data-href="{!! route('admin.order.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('set.read'))
                <li class="{!! active_class('admin.set*') !!}">
                    <a href="{!! route('admin.set.index') !!}">
                        <i class="fa fa-wrench"></i>
                        <span>@lang('labels.sets')</span>

                        @if ($user->hasAccess('set.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_set')"
                                   data-href="{!! route('admin.set.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('bouquet.read'))
                <li class="{!! active_class('admin.bouquet*') !!}">
                    <a href="{!! route('admin.bouquet.index') !!}">
                        <i class="fa fa-paw"></i>
                        <span>@lang('labels.bouquets')</span>

                        @if ($user->hasAccess('bouquet.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_bouquet')"
                                   data-href="{!! route('admin.bouquet.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('product.read'))
                <li class="{!! active_class('admin.product.*') !!}">
                    <a href="{!! route('admin.product.index') !!}">
                        <i class="fa fa-product-hunt"></i>
                        <span>@lang('labels.products')</span>

                        @if ($user->hasAccess('product.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_product')"
                                   data-href="{!! route('admin.product.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('subscription.read'))
                <li class="{!! active_class('admin.subscription*') !!}">
                    <a href="{!! route('admin.subscription.index') !!}">
                        <i class="fa fa-envelope-open-o"></i>
                        <span>@lang('labels.subscriptions')</span>

                        @if ($user->hasAccess('subscription.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_subscription')"
                                   data-href="{!! route('admin.subscription.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('sale.read'))
                <li class="{!! active_class('admin.sale*') !!}">
                    <a href="{!! route('admin.sale.index') !!}">
                        <i class="fa fa-cc"></i>
                        <span>@lang('labels.sales')</span>

                        @if ($user->hasAccess('sale.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_sale')"
                                   data-href="{!! route('admin.sale.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('individual.read'))
                <li class="{!! active_class('admin.individual*') !!}">
                    <a href="{!! route('admin.individual.index') !!}">
                        <i class="fa fa-gift"></i>
                        <span>@lang('labels.individuals')</span>
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('code.read'))
                <li class="{!! active_class('admin.code*') !!}">
                    <a href="{!! route('admin.code.index') !!}">
                        <i class="fa fa-key"></i>
                        <span>@lang('labels.codes')</span>

                        @if ($user->hasAccess('code.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_code')"
                                   data-href="{!! route('admin.code.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('discount.read'))
                <li class="{!! active_class('admin.discount*') !!}">
                    <a href="{!! route('admin.discount.index') !!}">
                        <i class="fa fa-percent"></i>
                        <span>@lang('labels.discounts')</span>

                        @if ($user->hasAccess('discount.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_discount')"
                                   data-href="{!! route('admin.discount.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif

            @if ($user->hasAccess('group') || $user->hasAccess('user.read'))
                <li class="header">@lang('labels.users')</li>
            @endif
            @if ($user->hasAccess('user.read'))
                <li class="{!! active_class('admin.user.index*') !!}">
                    <a href="{!! route('admin.user.index') !!}">
                        <i class="fa fa-user"></i>
                        <span>@lang('labels.users')</span>

                        @if ($user->hasAccess('user.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_user')"
                                   data-href="{!! route('admin.user.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif
            @if ($user->hasAccess('group'))
                <li class="{!! active_class('admin.group.index*') !!}">
                    <a href="{!! route('admin.group.index') !!}">
                        <i class="fa fa-users"></i>
                        <span>@lang('labels.groups')</span>

                        @if ($user->hasAccess('group.create'))
                            <small class="label create-label pull-right bg-green" title="@lang('labels.add_group')"
                                   data-href="{!! route('admin.group.create') !!}">
                                <i class="fa fa-plus"></i>
                            </small>
                        @endif
                    </a>
                </li>
            @endif
        </ul>
    </section>
</aside>
