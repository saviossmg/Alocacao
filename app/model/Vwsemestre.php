<?php





/**
 * Vwsemestre
 *
 * @Table(name="vwsemestre")
 * @Entity
 */
class Vwsemestre
{
    /**
     * @var integer
     *
     * @Column(name="Id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="Descricao", type="string", length=45, nullable=false)
     */
    private $descricao;

    /**
     * @var \DateTime
     *
     * @Column(name="DataInicio", type="date", nullable=false)
     */
    private $datainicio;

    /**
     * @var \DateTime
     *
     * @Column(name="DataFim", type="date", nullable=false)
     */
    private $datafim;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set descricao
     *
     * @param string $descricao
     *
     * @return Vwsemestre
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get descricao
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set datainicio
     *
     * @param \DateTime $datainicio
     *
     * @return Vwsemestre
     */
    public function setDatainicio($datainicio)
    {
        $this->datainicio = $datainicio;

        return $this;
    }

    /**
     * Get datainicio
     *
     * @return \DateTime
     */
    public function getDatainicio()
    {
        return $this->datainicio;
    }

    /**
     * Set datafim
     *
     * @param \DateTime $datafim
     *
     * @return Vwsemestre
     */
    public function setDatafim($datafim)
    {
        $this->datafim = $datafim;

        return $this;
    }

    /**
     * Get datafim
     *
     * @return \DateTime
     */
    public function getDatafim()
    {
        return $this->datafim;
    }
}

