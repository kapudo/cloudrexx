<?php
include_once('../testCases/DoctrineTestCase.php');

class PageTest extends DoctrineTestCase
{
    public function testValidation() {
        $node = new \Cx\Model\ContentManager\Node();
        $p = new \Cx\Model\ContentManager\Page();

        $p->setLang(1);
        $p->setTitle('testpage');
        $p->setNode($node);
        $p->setUsername('user');
        $p->setCmd('should_be_valid');

        //shouldn't raise a ValidationException
        self::$em->persist($node);
        self::$em->persist($p);
        self::$em->flush();
    }

    public function testLoggable() {
        $root = new \Cx\Model\ContentManager\Node();
        $n = new \Cx\Model\ContentManager\Node();

        $n->setParent($root);

        $p = new \Cx\Model\ContentManager\Page();

        $p->setLang(1);
        $p->setTitle('testpage');
        $p->setNode($n);
        $p->setUsername('user');

        self::$em->persist($root);
        self::$em->persist($n);

        self::$em->persist($p);

        self::$em->flush();

        //now, create a log
        $p->setTitle('testpage_changed');
        self::$em->persist($p);

        self::$em->flush();

        //now, agiiin
        $p->setTitle('testpage_changed_2');
        self::$em->persist($p);

        self::$em->flush();

        $repo = self::$em->getRepository('Gedmo\Loggable\Entity\LogEntry'); // we use default log entry class

        $this->assertEquals('testpage_changed_2',$p->getTitle());
        $repo->revert($p,1);
        $this->assertEquals('testpage',$p->getTitle());
        $repo->revert($p,2);
        $this->assertEquals('testpage_changed',$p->getTitle());
        $repo->revert($p,1);
        $this->assertEquals('testpage',$p->getTitle());
    }

    public function testSlugGeneration() {
        $p = new \Cx\Model\ContentManager\Page();
        $p->setTitle('test');
        $this->assertEquals('test', $p->getSlug());

        $p = new \Cx\Model\ContentManager\Page();
        $p->setTitle('test with space');
        $this->assertEquals('test-with-space', $p->getSlug());

        $p = new \Cx\Model\ContentManager\Page();
        $p->setTitle('test ümläut');
        $this->assertEquals('test-mlut', $p->getSlug());

        $p = new \Cx\Model\ContentManager\Page();
        $p->setTitle('123');
        $this->assertEquals('123', $p->getSlug());
    }

    public function testImplicitExplicitSlugSetting() {
        $p = new \Cx\Model\ContentManager\Page();
        $p->setTitle('test');
        $this->assertEquals('test', $p->getSlug());

        $p->setTitle('thisshouldntaffecttheslug');
        $this->assertEquals('test', $p->getSlug());

        $p->setSlug('butThisShould');
        $this->assertEquals('butThisShould', $p->getSlug());
    }
}