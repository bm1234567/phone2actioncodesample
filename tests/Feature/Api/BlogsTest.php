<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class BlogsTest extends TestCase
{
    const LOGIN_ENDPOINT = '/login';
    const API_ENDPOINT = '/api';
    const API_BLOG_ENDPOINT = self::API_ENDPOINT . '/blogs';

    public function testApiRequiresAuthentication()
    {
        //Given no user is logged in
        //Then it should redirect to the login

        $response = $this->get(self::API_ENDPOINT);

        $response->assertRedirect(self::LOGIN_ENDPOINT);
    }

    public function testBlogApiRequiresAuthentication()
    {
        $response = $this->get(self::API_BLOG_ENDPOINT);

        $response->assertRedirect(self::LOGIN_ENDPOINT);
    }

    /**
     * @return void
     */
    public function testStatusSuccess()
    {
        $response = $this->getActingAs($this->createUser(), self::API_BLOG_ENDPOINT);

        $response->assertStatus(200);
    }

    public function testBasicJsonStructure()
    {
        $response = $this->getActingAs($this->createUser(), self::API_BLOG_ENDPOINT);

        $response->assertJsonStructure(
            [
                'data' =>
                    [
                        [
                            'title',
                            'description',
                            'author'
                        ]
                    ]
            ]
        );
    }

    public function testBlogIndexGetsNoMoreThan15()
    {
        $response = $this->getActingAs($this->createUser(), self::API_BLOG_ENDPOINT);

        $blogs = $this->getBlogsFromResponse($response);

        $this->assertTrue(count($blogs) <= 15);
    }

    public function testBlogIndexShowsMostRecentFirst()
    {
        $response = $this->getActingAs($this->createUser(), self::API_BLOG_ENDPOINT);

        $blogs = $this->getBlogsFromResponse($response);

        $firstBlog = $blogs[0];
        $secondBlog = $blogs[1];

        $this->assertTrue($firstBlog->created_at > $secondBlog->created_at);
    }

    public function testBlogIndexCanGetPage1()
    {
        //get response of calling first page of blogs
        $response = $this->getActingAs($this->createUser(), self::API_BLOG_ENDPOINT . '?page=1');

        $blogs = $this->getBlogsFromResponse($response);

        $this->assertTrue(count($blogs) > 0);
    }

    public function testBlogIndexCanGetPage2()
    {
        //get response of calling first page of blogs
        $response = $this->getActingAs($this->createUser(), self::API_BLOG_ENDPOINT . '?page=2');

        $blogs = $this->getBlogsFromResponse($response);

        $this->assertTrue(count($blogs) > 0);
    }

    public function testPage1AndPage2AreDifferent()
    {
        $user = $this->createUser();

        $responsePageOne = $this->getActingAs($user, self::API_BLOG_ENDPOINT . '?page=1');
        $blogsPageOne = $this->getBlogsFromResponse($responsePageOne);

        $responsePageTwo = $this->getActingAs($user, self::API_BLOG_ENDPOINT . '?page=2');
        $blogsPageTwo = $this->getBlogsFromResponse($responsePageTwo);

        $this->assertTrue($blogsPageOne != $blogsPageTwo);
    }



    protected function createUser()
    {
        return factory(\App\User::class)->create();

    }

    protected function getActingAs($user, $url='/')
    {
//        return $this->actingAs($user)->get($url);
//        return Passport::actingAs($user, ['read-blogs'])->get($url);

        Passport::actingAs(
            $this->createUser(),
            ['read-blogs']
        );

        return $this->get($url);
    }

    protected function getBlogsFromResponse($response)
    {
        //get blogs out of response
        $content = json_decode($response->getContent());
        return $content->data;
    }
}
