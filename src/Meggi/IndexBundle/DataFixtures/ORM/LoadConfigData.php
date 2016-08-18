<?php
namespace Meggi\IndexBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Meggi\IndexBundle\Entity\Config;
use Application\Sonata\UserBundle\Entity\User;
use Meggi\IndexBundle\Entity\Banner;
use Meggi\IndexBundle\Entity\Category;

class LoadIndexData implements FixtureInterface
{
    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $config = new Config();
        $config->setTitle('Копирайт');
        $config->setValue('© 2015 Meggi, Минск, Республика Беларус');
        $config->setKeyValue('COPYRIGHT');
        $manager->persist($config);

        $config = new Config();
        $config->setTitle('Email на который будет отправляться уведомление');
        $config->setValue('sliml2@mail.ru');
        $config->setKeyValue('EMAIL_TO');
        $manager->persist($config);

        $config = new Config();
        $config->setTitle('Партнерам');
        $config->setValue('');
        $config->setKeyValue('PARTNERS');
        $manager->persist($config);

        $config = new Config();
        $config->setTitle('Компания');
        $config->setValue('');
        $config->setKeyValue('COMPANY');
        $manager->persist($config);

        $config = new Config();
        $config->setTitle('EMAIL');
        $config->setValue('sliml2@mail.ru');
        $config->setKeyValue('EMAIL_TO');
        $manager->persist($config);

        $banner = new Banner();
        $banner->setTitle('Бытовая химия для дома Meggi Home');
        $banner->setDescription("Моющие средства для одежды, чистящие средства, жидкое мыло и многое другое");
        $banner->setPosition(0);
        $banner->setColor('0db7c8');

        $banner->setLink('http://google.com');

        $manager->persist($banner);

        $banner = new Banner();
        $banner->setTitle('Гигиенические средства Meggi');
        $banner->setDescription('Гигиенические прокладки и тампоны, ватные диски и палочки');
        $banner->setPosition(1);
        $banner->setColor('ce72cd');

        $banner->setLink('http://google.com');

        $manager->persist($banner);

        $banner = new Banner();
        $banner->setTitle('Влажные салфетки Softex');
        $banner->setDescription('Косметические, для интимной гигиены, детские, универсальные');
        $banner->setPosition(0);
        $banner->setColor('65b7e2');

        $banner->setLink('http://google.com');

        $manager->persist($banner);

        $banner = new Banner();
        $banner->setTitle('Зубные пасты Fresh&White');
        $banner->setDescription('Детские, для интимной гигиены, универсальные');
        $banner->setPosition(1);
        $banner->setColor('b9d343');

        $banner->setLink('http://google.com');
        $manager->persist($banner);

        $cateogy = new Category();
        $cateogy->setName('Моющие средства для одежды');
        $manager->persist($cateogy);

        $cateogy = new Category();
        $cateogy->setName('Кондиционеры для одежды');
        $manager->persist($cateogy);

        $cateogy = new Category();
        $cateogy->setName('Средства для мытья окон');
        $manager->persist($cateogy);

        $cateogy = new Category();
        $cateogy->setName('Универсальные моющие средства');
        $manager->persist($cateogy);

        $cateogy = new Category();
        $cateogy->setName('Средства для мытья посуды');
        $manager->persist($cateogy);

        $User = new User();
        $User->setEmail("admin@admin.by");
        $User->setPlainPassword("admin");
        $User->setSuperAdmin(true);
        $User->setUsername("admin");
        $User->setEnabled(true);

        $manager->persist($User);
        $manager->flush();


    }
}
