<?php

namespace Tests\Browser;

use Carbon\Carbon;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BlogIndexViewTest extends DuskTestCase
{

    public function testViewRequiresAuthentication()
    {
        //Given not user is logged in
        //Then we should be redirected to login

        $this->browse(function(Browser $browser){
           $browser
                ->visit('/blogs')
                ->assertSee('Login');
        });
    }

    public function testViewLoadsAfterAuthentication()
    {
        $this->browse(function(Browser $browser){
            $browser
                ->loginAs(\App\User::first())
                ->visit('/blogs')
                ->assertSee('Blogs')
                ->logout();
        });
    }

    public function testListShows()
    {
        $this->browse(function(Browser $browser){
           $browser
               ->loginAs(\App\User::first())
               ->visit('/blogs')
               ->waitFor('.blog')
               ->assertPresent('.blog')
               ->logout();
        });
    }

    public function testListShowsImportantFields()
    {
        $this->browse(function(Browser $browser){

            //get first record
            $firstBlog = \App\Blog::orderBy('created_at', 'desc')->first();

            $browser
                ->loginAs(\App\User::first())
                ->visit('/blogs')
                ->waitFor('.blog')
                ->waitFor('.blog-title')
                ->assertSeeIn('.blog-title', $firstBlog->title)
                ->assertSeeIn('.blog-created-at', $firstBlog->created_at->format('Y'))
                ->assertPresent('.blog-description')
                ->logout();
        });
    }
}
