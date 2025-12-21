<?php
/**
 * Created by Vscode.
 * User: Andy
 * Date: 2025-12-18
 * This Request is used to delete tracking information.
 */

namespace ThankSong\Track123\Request;

use InvalidArgumentException;
use ThankSong\Track123\Response\Response;

class DeleteTrackingsRequest extends Request {
    /**
     * 路径端点常量
     * @var string
     */
    public const ENDPOINT = '/tk/v2.1/track/delete';

    /**
     * Summary of trackings
     * @var array
     */
    protected array $trackings = [];

    /**
     * DeleteTrackingReequest constructor.
     * @param array $trackings
     */
    public function __construct(array $trackings = []){
        $this->setEndpoint(self::ENDPOINT);
        if(!empty($trackings)){
            $this->setDeleteTrackings($trackings);
        }
    }

    /**
     * 设置单个需要删除的追踪信息
     * @param string $trackNo
     * @param string $courierCode
     * @return static
     */
    public function setDeleteTracking(string $trackNo,string $courierCode){
        $this->setParams([
            'trackNo' => $trackNo,
            'courierCode' => $courierCode
        ]);
        return $this;
    }

    /**
     * 批量设置需要删除的追踪信息
     * @param array $trackings
     * @return static
     */
    public function setDeleteTrackings(array $trackings){
        $this->trackings = [...$this->trackings,...$trackings];
        return $this;
    }

    /**
     * 发送请求
     */
    public function send(): Response{
        $this->setParams($this->trackings);
        return parent::send();
    }


    /**
     * 验证
     * @throws InvalidArgumentException
     * @return void
     */
    public function validate(){
        foreach ($this->trackings as $index => $tracking) {
            if (empty($tracking['trackNo'] ?? null)) {
                $row = $index + 1;
                $errors[]= "第{$row}个跟踪号不能为空";
            }
            if(empty($tracking['courierCode'] ?? null)){
                $row = $index + 1;
                $errors[]= "第{$row}个快递公司代码不能为空";
            }
        }
        if(!empty($errors)){
            throw new InvalidArgumentException(implode("\n", $errors));
        }
    }




}