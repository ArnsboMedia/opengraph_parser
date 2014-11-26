<?php
namespace OpenGraphParser;
class OpenGraphParserTest extends \PHPUnit_Framework_TestCase
{

    public function setUp() {
        $this->subject = new OpenGraphParser();
    
    }
    public function testClassCanBeInstantiated() {
        $this->assertInstanceOf('OpenGraphParser\OpenGraphParser', $this->subject);
    }


    public function testParseUrlReturnsResultObject() {
        $result = $this->subject->parse('');
        $this->assertInstanceOf('OpenGraphParser\Result', $result);
    }

    public function testParseListReturnsAResultPerElement() {
        $result = $this->subject->parseList(array('', '', ''));

        $this->assertInstanceOf('OpenGraphParser\Result', $result[0]);
        $this->assertInstanceOf('OpenGraphParser\Result', $result[1]);
        $this->assertInstanceOf('OpenGraphParser\Result', $result[2]);
    }

    public function testParseUsesFetchStrategy() {

        $strategy = $this->getMockBuilder('OpenGraphParser\FetchStrategy')
            ->disableOriginalConstructor()
            ->getMock();

        $strategy->expects($this->once())
                 ->method('get')
                 ->with($this->equalTo('something'));

        $this->subject->setFetchStrategy($strategy);


        $this->subject->parse('something');
    }

    public function testResultObjectHasOriginalFetchedBody() {
        $strategy = $this->getMockBuilder('OpenGraphParser\AbstractFetchStrategy')
            ->setMethods(array('get_content'))
            ->disableOriginalConstructor()
            ->getMock();

        $strategy->expects($this->once())
                 ->method('get_content')
                 ->with($this->equalTo('something'))
                 ->willReturn('content');

        $this->subject->setFetchStrategy($strategy);

        $result = $this->subject->parse('something');

        $this->assertEquals('content', $result->getOriginalContent());
    }

}

