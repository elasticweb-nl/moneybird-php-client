<?php

namespace Picqer\Financials\Moneybird\Actions;

/**
 * Class Storable.
 */
trait Storable
{
    use BaseTrait;

    /**
     * @return mixed
     *
     * @throws \Picqer\Financials\Moneybird\Exceptions\ApiException
     */
    public function save()
    {
        if ($this->exists()) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    /**
     * @return mixed
     *
     * @throws \Picqer\Financials\Moneybird\Exceptions\ApiException
     */
    public function insert()
    {
        $result = $this->connection()->post($this->getEndpoint(), $this->jsonWithNamespace());

        if (method_exists($this, 'clearDirty')) {
            $this->clearDirty();
        }

        return $this->selfFromResponse($result);
    }

    /**
     * @return mixed
     *
     * @throws \Picqer\Financials\Moneybird\Exceptions\ApiException
     */
    public function update()
    {
        $result = $this->connection()->patch($this->getEndpoint() . '/' . urlencode($this->id), $this->jsonWithNamespace());

        if ($result === 200) {
            if (method_exists($this, 'clearDirty')) {
                $this->clearDirty();
            }

            return true;
        }

        return $this->selfFromResponse($result);
    }
}
