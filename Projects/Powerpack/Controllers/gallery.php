<?php namespace Project\Controllers;

use DBGrid, Post, Folder, GalleryCategories, Method, File, URI, Upload, Images, Users;

class Gallery extends Controller
{
    public function create(String $params = NULL)
    {
        if( Post::saveButton() )
        {
            if( URI::get('process') === 'edit' )
            {
                $oldName = GalleryCategories::rowId($postId = URI::get('column'))->name;
                
                if( $oldName !== ($newName = Method::post('gallery_categories:name')) )
                {
                    $oldPath = UPLOADS_DIR . $oldName;
                    
                    if( File::rename($oldPath, UPLOADS_DIR . ($newName = Method::post('gallery_categories:name'))) )
                    {
                        if( GalleryCategories::updateId(['name' => $newName], $postId) )
                        {
                            Masterpage::success('success');
                            Users::activity('edited gallery category');
                        }
                        else
                        {
                            Masterpage::error('error');
                        }
                    } 
                }
            }
            else
            {
                if( Folder::create(UPLOADS_DIR . Method::post('gallery_categories:name')) )
                {
                    Masterpage::success('success');
                    Users::activity('created gallery category');
                }
            }             
        }

        if( Post::deleteButton() )
        {
            if( is_dir($path = UPLOADS_DIR . GalleryCategories::rowId($postId = Post::id())->name) )
            {
                Folder::delete($path);
            }     

            Images::deleteCategory_id($postId);

            Masterpage::success('success');

            Users::activity('deleted gallery category');
        }

        View::pageTitle('Create Gallery')->pageTitle
        (
            DBGrid::search('id', 'name', 'date')
                  ->exclude('date')
                  ->columns('id as ID', 'name as Name', 'date as Date')
                  ->create('gallery_categories')
        );
    }

    public function upload(String $params = NULL)
    {
        if( Post::uploadSubmit() )
        {
            if( empty(Post::categoryId()) )
            {
                Masterpage::error('error');
            }
            else
            {
                Upload::settings
                ([
                    'encode'     => 'md5',
                    'extensions' => 'jpg|png|gif|pdf|xls|xlsx|doc|docx|csv|avi|mp4|mp3',
                    'maxsize'    => 1000000
                ])->start('upload', UPLOADS_DIR . GalleryCategories::rowId($categoryId = Post::categoryId())->name);
                
                if( ! Upload::error() )
                {
                    $infos = Upload::info('encodeName');

                    foreach( $infos as $info )
                    {
                        Images::insert
                        ([
                            'category_id' => $categoryId,
                            'image'       => $info
                        ]);
                    }
                    
                    Masterpage::success('success');

                    Users::activity('uploaded photo');
                }
                else
                {
                    Masterpage::error('error');
                }
            }   
        }

        View::pageTitle('Upload Image')
            ->pageBody(View::get('gallery/gallery-form', true));
    }   

    public function list(String $params = NULL)
    {
        if( Post::deleteButton() )
        {   
            $image = Images::rowId($postId = Post::id());

            Images::deleteId($postId);

            $category = GalleryCategories::rowId($image->category_id)->name;

            $path = UPLOADS_DIR . $category . '/' . $image->image;

            if( is_file($path) )
            {
                Folder::delete($path);                
            }
            
            Masterpage::success('success');

            Users::activity('deleted photo');
        }

        View::pageTitle('Images')->pageTitle
        (
            DBGrid::exclude('date', 'image')
                  ->hide('addButton')
                  ->inputs(['category_id' => function($form, $name, $value)
                  { 
                      return $form->table('gallery_categories')->select($name, ['id' => 'name'], $value);
                  }])
                  ->outputs
                  ([
                        'category_id' => function($html, $value)
                        { 
                            return $this->category = GalleryCategories::rowId($value)->name;
                        },
                        'image' => function($html, $value)
                        { 
                            return $html->image(UPLOADS_DIR . $this->category.'/' . $value, 50, 50);
                        }
                  ])
                  ->columns('id as ID', 'category_id as CategoryID', 'image as Image', 'date as Date')
                  ->limit(10)
                  ->create('images')
        );
    }
}
