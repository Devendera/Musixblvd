<?php

namespace App\Rules;

use App\Project;
use Illuminate\Contracts\Validation\Rule;

class ValidDuplicateStreamingURL implements Rule
{
    private $projectId;
    private $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $project = Project::where($attribute, $this->urlToDomain($value))->first();

        if($project){
            $this->projectId = $project['id'];
            $this->attribute = $attribute;
            return false;
        }

        return true;
    }


    public function message()
    {
        return [
            'error' => "Duplicate url. Url already exists on another project.",
            'projectId' => $this->projectId
        ];
    }

    public function urlToDomain($url) {

        // in case scheme relative URI is passed, e.g., //www.google.com/
        $input = trim($url, '/');

        // If scheme not included, prepend it
        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://' . $input;
        }

        $urlParts = parse_url($input);

        // remove www
        $domain = preg_replace('/^www\./', '', $urlParts['host']);

        return $domain;
    }
}
