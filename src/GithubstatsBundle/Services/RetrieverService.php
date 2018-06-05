<?php

namespace GithubstatsBundle\Services;


class RetrieverService extends ApiCaller {

    private $apiURL = 'https://api.github.com/';

    /**
     * @param string $userName
     *
     * @return array
     * @throws \RuntimeException
     */

    function getUserData($userName){

        $resultArray = array();

        try {
          $result = $this->call($this->apiURL.'users/'.$userName);
        } catch (\Exception $e) {
            throw $e;
        }

        if(isset($result->contents['name'])){
            $resultArray['name'] = $result->contents['name'];
        }else{
            $resultArray['name'] = '';
        }

        if(isset($result->contents['blog'])){
            $resultArray['website'] = $result->contents['blog'];
        }else{
            $resultArray['website'] = '';
        }

        if(isset($result->contents['followers'])){
            $resultArray['followers'] = $result->contents['followers'];
        }else{
            $resultArray['followers'] = '';
        }

        if(isset($result->contents['public_repos'])){
            $resultArray['public_repos'] = $result->contents['public_repos'];
        }else{
            $resultArray['public_repos'] = '';
        }

        if(isset($result->contents['location'])){
            $resultArray['location'] = $result->contents['location'];
        }else{
            $resultArray['location'] = '';
        }

        if(isset($result->contents['created_at'])){
            $resultArray['created_at'] = date('Y', strtotime($result->contents['created_at']));
        }else{
            $resultArray['created_at'] = '';
        }

        return $resultArray;

    }


    /**
     * @param string $userName
     *
     * @return array
     * @throws \RuntimeException
     */

    function getRepositoryData($userName){

        $reposArray = array();
        $resultArray = array();

        $languagesArray = array();
        $languagesResultArray = array();
        $result = array();

        try {
            $page = 0;
            do{
                $page++;
                $resultCall = $this->call($this->apiURL.'users/'.$userName.'/repos?page='.$page);

                $result = array_merge($result, $resultCall->contents);
            }while(!empty($resultCall->contents));

        } catch (\Exception $e) {
            throw $e;
        }

        foreach ($result as $key=>$value){
            $recordArray = array();
            if(isset($value['language'])){
                $recordArray['language'] = $value['language'];
            }else{
                $recordArray['language'] = '';
            }

            if(isset($value['name'])){
                $recordArray['name'] = $value['name'];
            }else{
                $recordArray['name'] = '';
            }

            if(isset($value['description'])){
                $recordArray['description'] = $value['description'];
            }else{
                $recordArray['description'] = '';
            }

            if(isset($value['html_url'])){
                $recordArray['html_url'] = $value['html_url'];
            }else{
                $recordArray['html_url'] = '';
            }

            if(isset($value['homepage'])){
                $recordArray['homepage'] = $value['homepage'];
            }else{
                $recordArray['homepage'] = '';
            }

            if(isset($value['watchers_count'])){
                $recordArray['watchers_count'] = $value['watchers_count'];
            }else{
                $recordArray['watchers_count'] = '';
            }

            if(isset($value['forks_count'])){
                $recordArray['forks_count'] = $value['forks_count'];
            }else{
                $recordArray['forks_count'] = '';
            }

            if(isset($value['stargazers_count'])){
                $recordArray['stargazers_count'] = $value['stargazers_count'];
            }else{
                $recordArray['stargazers_count'] = '';
            }

            if(isset($value['size'])){
                $recordArray['size'] = $value['size'];
            }else{
                $recordArray['stargazers_count'] = '0';
            }

            if(isset($value['languages_url'])){
                $recordArray['languages_url'] = $value['languages_url'];
            }else{
                $recordArray['languages_url'] = '';
            }

            if(isset($value['created_at'])){
                $recordArray['created_at'] = date('Y', strtotime($value['created_at']));
            }else{
                $recordArray['created_at'] = '';
            }

            if(isset($value['updated_at'])){
                $recordArray['updated_at'] = date('Y', strtotime($value['updated_at']));
            }else{
                $recordArray['updated_at'] = '';
            }

            if(!empty($recordArray['language'])){
                if(!isset($languagesArray[$recordArray['language']])){
                    $languagesArray[$recordArray['language']] = $value['size'];
                }else{
                    $languagesArray[$recordArray['language']] += $value['size'];
                }
            }

            $reposArray[$key] = $recordArray;

        }

        arsort($languagesArray);
        $languageSum = array_sum($languagesArray);

            if($languageSum > 0){
                foreach ($languagesArray as $key=>$value){
                    $recordArray = array();
                    $recordArray['language'] = $key;
                    $recordArray['count'] = $value;
                    $recordArray['percent'] = round($value / $languageSum,4) * 100;
                    $languagesResultArray[] = $recordArray;
                }

            }

        usort($reposArray, array($this,'cmp'));

        $resultArray['most_popular_repos'] = $reposArray;
        $resultArray['languages'] =$languagesResultArray;

        return $resultArray;

    }

    function cmp($a, $b)
    {
        return $a["stargazers_count"] < $b["stargazers_count"];
    }

}