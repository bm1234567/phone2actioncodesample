import { mount } from '@vue/test-utils';
import Blogs from '../../resources/assets/js/components/Blogs.vue'
import expect from 'expect'
import moxios from 'moxios'
import moment from 'moment'

describe ('Blogs', () => {
    const API_BLOG_ENDPOINT = '/api/blogs';

    let now = () => {
        return moment();
    }

    let wrapper;

    let mockBlogs = () => {
        return [
            {
                id: 1,
                title: 'Test Title from mock blog',
                description: 'Test Description',
                author: 'Test Author',
                created_at: now()
            }
        ]
    }

    let asyncAssertToBe = (selector, value, done) => {
        try {
            expect(selector).toBe(value)
            done()
        } catch (e) {
            done(e)
        }
    }

    let asyncAssertContains = (selector, value, done) => {
        try {
            expect(selector).toContain(value)
            done()
        } catch (e) {
            done(e)
        }
    }

    beforeEach(() => {
        moxios.install();
        wrapper = mount(Blogs);
    });

    afterEach(() => {
        moxios.uninstall();
    });

    it('loads blogs container', () => {
        expect(wrapper.find('.blogs').exists()).toBe(true);
    });

    it ('lists blog entries', (done) => {
        moxios.stubRequest(API_BLOG_ENDPOINT, {
            status: 200,
            response: {
                data: mockBlogs()
            }
        });

        moxios.wait(() => {
            asyncAssertToBe(wrapper.find('.blog').exists(), true, done)
        });
    });

    it('shows blog title', (done) => {
        moxios.stubRequest(API_BLOG_ENDPOINT, {
            status: 200,
            response: {
                data: mockBlogs()
            }
        });

        moxios.wait(() => {
            asyncAssertContains(wrapper.find('.blog-title').text(), 'Test Title', done)
        });
    });

    it('shows date created', (done) => {
        moxios.stubRequest(API_BLOG_ENDPOINT, {
            status: 200,
            response: {
                data: mockBlogs()
            }
        });

        moxios.wait(() => {
            asyncAssertContains(wrapper.find('.blog-created-at').text(), now().year(), done)
        });
    });

    it('loads blog description', (done) => {
        moxios.stubRequest(API_BLOG_ENDPOINT, {
            status: 200,
            response: {
                data: mockBlogs()
            }
        });

        moxios.wait(() => {
            asyncAssertContains(wrapper.find('.blog-description').text(), 'Test Description', done)
        });
    });

    it('has a toggle for blog description', (done) => {
        moxios.stubRequest(API_BLOG_ENDPOINT, {
            status: 200,
            response: {
                data: mockBlogs()
            }
        });

        moxios.wait(() => {
            asyncAssertToBe(wrapper.find('.blog-body-toggle').exists(), true, done);
        });
    });

    it('has a toggle for blog description that shows/hides description', (done) => {
        moxios.stubRequest(API_BLOG_ENDPOINT, {
            status: 200,
            response: {
                data: mockBlogs()
            }
        });

        moxios.wait(() => {

            try {
                let toggle = wrapper.find('.blog-body-toggle');
                let desc = wrapper.find('.blog-description');

                expect(desc.isVisible()).toBe(false);

                toggle.trigger('click');

                expect(desc.isVisible()).toBe(true);

                done();
            } catch (e) {
                done(e);
            }
        });
    });

    it ('loads the author field', (done) => {
        moxios.stubRequest(API_BLOG_ENDPOINT, {
            status: 200,
            response: {
                data: mockBlogs()
            }
        });

        moxios.wait(() => {
           asyncAssertToBe(wrapper.find('.blog-author').exists(), true, done);
        });
    });

    it ('loads author data', (done) => {
        moxios.stubRequest(API_BLOG_ENDPOINT, {
            status: 200,
            response: {
                data: mockBlogs()
            }
        });

        moxios.wait(() => {
           try {
               expect(wrapper.find('.blog-author').text()).toContain('Test Author');
               done()
           } catch(e){
               done(e)
           }
        });
    });

    it ('shows pagination container', (done) => {
       moxios.stubRequest(API_BLOG_ENDPOINT, {
           status: 200,
           response: {
               data: mockBlogs(),
           }
       });

        moxios.wait(() => {
            try {
                expect(wrapper.find('.pagination').exists()).toBe(true);
                done();
            } catch(e){
                done(e);
            }
        })
    });

    it ('shows current page in pagination', (done) => {
        moxios.stubRequest(API_BLOG_ENDPOINT, {
            status: 200,
            response: {
                data: mockBlogs(),
                current_page: 1
            }
        });

        moxios.wait(() => {
            try {
                expect(wrapper.find('.current-page').text()).toBe('1');
                done();
            } catch(e){
                done(e);
            }
        });
    });

    it ('shows ability to goto the next page of blogs', () => {
        expect(wrapper.find('.next-page').exists()).toBe(true);
    });


    it ('goes to next page of blogs when next page link is clicked', () => {

        moxios.stubRequest(API_BLOG_ENDPOINT, {
            status: 200,
            response: {
                data: mockBlogs(),
                current_page: 1
            }
        });

        return new Promise((resolve, reject) => {
            moxios.wait(() => {

                let expected = '1';
                let actual = wrapper.find('.current-page').text();

                if (actual !== expected) {
                    reject(`"${actual}" is not equal to "${expected}"`);
                } else {

                    let request = moxios.requests.mostRecent();
                    request.respondWith({
                        status: 200,
                        response: {
                            data: mockBlogs(),
                            // current_page: currentPageFromServer
                        }
                    }).then(() => {

                        wrapper.find('.next-page').trigger('click');

                        //**
                        // Given NextPage button is clicked.
                        // And after waiting, we get a response from the server
                        // Then see if current page in the pagination goes to the current_page
                        //  passed from the server
                        //**
                        moxios.wait(() => {
                            let request = moxios.requests.mostRecent();
                            request.respondWith({
                                status: 200,
                                response: {
                                    data: mockBlogs(),
                                    current_page: 2
                                }
                            }).then(() => {
                                let expected = '2';
                                let actual = wrapper.find('.current-page').text();

                                if (actual !== expected) {
                                    reject(`"${actual}" is not equal to "${expected}"`);
                                } else {
                                    resolve('Resolved')
                                }
                            });
                        });
                    });
                }
            });
        }).catch(err => console.log(err));
    });
});
