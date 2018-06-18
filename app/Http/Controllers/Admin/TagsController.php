<?php

namespace IndianIra\Http\Controllers\Admin;

use IndianIra\Tag;
use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class TagsController extends Controller
{
    /**
     * Display all the tags.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $tags = $this->getAllTags();

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Store the tag data.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'              => 'bail|required|unique:tags,name|max:250',
            'short_description' => 'bail|nullable|max:250',
            'meta_title'        => 'bail|required|max:60',
            'meta_description'  => 'bail|required|max:160',
            'meta_keywords'     => 'nullable|max:150',
        ], [
            'name.required' => 'The tag name field is required.',
            'name.unique'   => 'The tag name has already been taken.',
            'name.max'      => 'The tag name may not be greater than 250 characters.',
        ]);

        $request['slug'] = str_slug($request->name);
        Tag::create($request->all());

        $tags = $this->getAllTags();

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Tags added successfully!',
            'location' => route('admin.tags'),
            'htmlResult' => view('admin.tags.table', compact('tags'))->render(),
        ]);
    }

    /**
     * Update the tag data of the given id.
     *
     * @param   integer  $id
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name'              => 'required|unique:tags,name,'.$id.'|max:250',
            'short_description' => 'nullable|max:250',
            'meta_title'        => 'required|max:60',
            'meta_description'  => 'required|max:160',
            'meta_keywords'     => 'nullable|max:250',
        ], [
            'name.required' => 'The tag name field is required.',
            'name.unique'   => 'The tag name has already been taken.',
            'name.max'      => 'The tag name may not be greater than 250 characters.',
        ]);

        $tag = Tag::find($id);

        if (! $tag) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Tag with that id cannot be found.'
            ]);
        }

        $request['slug'] = str_slug($request->name);
        $tag->update($request->all());

        $tags = $this->getAllTags();

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Tag updated successfully!',
            'location' => route('admin.tags'),
            'htmlResult' => view('admin.tags.table', compact('tags'))->render(),
        ]);
    }

    /**
     * Temporarily delete the tag data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        $tag = Tag::find($id);

        if (! $tag) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Tag with that id cannot be found.'
            ]);
        }

        $tag->delete();

        $tags = $this->getAllTags();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Tag deleted temporarily!',
            'location'   => route('admin.tags'),
            'htmlResult' => view('admin.tags.table', compact('tags'))->render(),
        ]);
    }

    /**
     * Restore the temporarily deleted tag data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function restore($id)
    {
        $tag = Tag::onlyTrashed()->find($id);

        if (! $tag) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Tag with that id cannot be found.'
            ]);
        }

        $tag->restore();

        $tags = $this->getAllTags();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Tag restored successfully!',
            'location'   => route('admin.tags'),
            'htmlResult' => view('admin.tags.table', compact('tags'))->render(),
        ]);
    }

    /**
     * Permanently delete the tag data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $tag = Tag::onlyTrashed()->find($id);

        if (! $tag) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Tag with that id cannot be found.'
            ]);
        }

        $tag->forceDelete();

        $tags = $this->getAllTags();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Tag destroyed successfully!',
            'location'   => route('admin.tags'),
            'htmlResult' => view('admin.tags.table', compact('tags'))->render(),
        ]);
    }

    /**
     * Get all the tags.
     *
     * @return  Illuminate\Database\Eloquent\Collection
     */
    protected function getAllTags()
    {
        return Tag::withTrashed()->orderBy('id', 'DESC')->get();
    }
}
