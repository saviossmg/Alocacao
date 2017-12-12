<?php
/**
 * TurmaProfessor
 *
 * @Table(name="TurmaProfessor", indexes={@Index(name="fk_alocacaoSalas_has_vwServidores_vwServidores1_idx", columns={"Professor"}), @Index(name="fk_alocacaoSalas_has_vwServidores_alocacaoSalas1_idx", columns={"AlocacaoSala"}), @Index(name="fk_TurmaProfessor_SemestreLetivo1_idx", columns={"SemestreLetivo"})})
 * @Entity
 */
class Turmaprofessor
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
     * @var \Semestreletivo
     *
     * @ManyToOne(targetEntity="Semestreletivo")
     * @JoinColumns({
     *   @JoinColumn(name="SemestreLetivo", referencedColumnName="Id")
     * })
     */
    private $semestreletivo;

    /**
     * @var \Alocacaosala
     *
     * @ManyToOne(targetEntity="Alocacaosala")
     * @JoinColumns({
     *   @JoinColumn(name="AlocacaoSala", referencedColumnName="Id")
     * })
     */
    private $alocacaosala;

    /**
     * @var \Vwservidor
     *
     * @ManyToOne(targetEntity="Vwservidor")
     * @JoinColumns({
     *   @JoinColumn(name="Professor", referencedColumnName="Id")
     * })
     */
    private $professor;


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
     * Set semestreletivo
     *
     * @param \Semestreletivo $semestreletivo
     *
     * @return Turmaprofessor
     */
    public function setSemestreletivo(\Semestreletivo $semestreletivo = null)
    {
        $this->semestreletivo = $semestreletivo;

        return $this;
    }

    /**
     * Get semestreletivo
     *
     * @return \Semestreletivo
     */
    public function getSemestreletivo()
    {
        return $this->semestreletivo;
    }

    /**
     * Set alocacaosala
     *
     * @param \Alocacaosala $alocacaosala
     *
     * @return Turmaprofessor
     */
    public function setAlocacaosala(\Alocacaosala $alocacaosala = null)
    {
        $this->alocacaosala = $alocacaosala;

        return $this;
    }

    /**
     * Get alocacaosala
     *
     * @return \Alocacaosala
     */
    public function getAlocacaosala()
    {
        return $this->alocacaosala;
    }

    /**
     * Set professor
     *
     * @param \Vwservidor $professor
     *
     * @return Turmaprofessor
     */
    public function setProfessor(\Vwservidor $professor = null)
    {
        $this->professor = $professor;

        return $this;
    }

    /**
     * Get professor
     *
     * @return \Vwservidor
     */
    public function getProfessor()
    {
        return $this->professor;
    }
}

