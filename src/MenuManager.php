<?php

/*
 * Menu Manager for Yii2
 *
 * @link      https://github.com/hiqdev/yii2-menumanager
 * @package   yii2-menumanager
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015, HiQDev (https://hiqdev.com/)
 */

namespace hiqdev\menumanager;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Menu Manager.
 *
 * Usage, in config:
 * ~~~
 * 'components' => [
 *     'menuManager' => [
 *         'class' => 'hiqdev\menumanager\MenuManager',
 *         'items' => [
 *             'main'        => [],
 *             'sidebar'     => [
 *                 'items' => [
 *                     'header' => [
 *                         'label' => Yii::t('app', 'MAIN NAVIGATION'),
 *                         'options' => ['class' => 'header'],
 *                     ],
 *                 ],
 *             ],
 *             'breadcrumbs' => [],
 *         ],
 *     ]
 * ]
 * ~~~
 * Usage, in code:
 * ~~~
 * Yii::$app->menuManager->sidebar->add([
 *     'label'  => 'Dashboard',
 *     'url'    => ['/hipanel'],
 *     'icon'   => 'fa-dashboard',
 *     'active' => Yii::$app->request->url === Yii::$app->homeUrl
 * ],['after'=>'header']);
 *
 * Yii::$app->menuManager->breadcrumbs->setItems([
 *     ['label' => Yii::t('app', 'Domains'), 'url' => ['index']],
 *     $this->title,
 * ]);
 * ~~~
 */
class MenuManager extends \hiqdev\collection\Manager implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    protected $_itemClass = 'hiqdev\menumanager\Menu';

    /**
     * @var bool is already bootstrapped.
     */
    protected $_isBootstrapped = false;

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        if ($this->_isBootstrapped) {
            return;
        }
        $app->pluginManager->bootstrap($app);
        $cached = null;
        if ($cached) {
            $app->menuManager->mset($cached);
        } else {
            if (is_array($app->pluginManager->menus)) {
                foreach ($app->pluginManager->menus as $config) {
                    $menu = Yii::createObject($config);
                    $this->{$menu->addTo}->addItems($menu->items, $menu->where);
                }
            }
            $cached = $this->toArray();
        }
        $this->_isBootstrapped = true;
    }
}
